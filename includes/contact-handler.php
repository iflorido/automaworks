<?php
/**
 * Gestor del formulario de contacto.
 * Mismo sistema que iflorido.es (PHPMailer, honeypot y tiempo mínimo),
 * con estas mejoras:
 *  - Las credenciales SMTP viven en includes/config.php (fuera de git, sin acceso web).
 *  - Token de sesión contra envíos ajenos al formulario.
 *  - Consentimiento RGPD obligatorio.
 *  - Patrón POST → redirección → GET: recargar la página no reenvía el mensaje.
 *
 * Debe cargarse ANTES de cualquier salida HTML.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/contacto/',
        'secure' => true, 'httponly' => true, 'samesite' => 'Lax',
    ]);
    session_name('aw_contacto');
    session_start();
}

if (!function_exists('aw_e')) {
    function aw_e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}

$config = is_file(__DIR__ . '/config.php') ? require __DIR__ . '/config.php' : null;
$publicEmail = $config['public_email'] ?? 'contacto@automaworks.es';

$needs = [
    'software'       => 'Una aplicación o herramienta a medida',
    'integraciones'  => 'Conectar herramientas (ERP, CRM, tienda online)',
    'automatizacion' => 'Automatizar tareas repetitivas',
    'ia'             => 'Inteligencia artificial',
    'dolibarr'       => 'Dolibarr ERP',
    'otro'           => 'Todavía no lo tengo claro',
];

$form   = ['nombre' => '', 'email' => '', 'empresa' => '', 'telefono' => '', 'necesidad' => '', 'mensaje' => ''];
$errors = [];
$sent   = isset($_GET['enviado']);

$newToken = function () {
    $_SESSION['aw_form_time']  = time();
    $_SESSION['aw_form_token'] = bin2hex(random_bytes(16));
};

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $newToken();
    return;
}

/* ---------- POST ---------- */
$limits = ['nombre' => 120, 'email' => 160, 'empresa' => 160, 'telefono' => 40, 'necesidad' => 40, 'mensaje' => 5000];
foreach ($form as $k => $_) {
    $v = trim(strip_tags((string) ($_POST[$k] ?? '')));
    if ($k !== 'mensaje') $v = preg_replace('/[\r\n]+/', ' ', $v);
    $form[$k] = mb_substr($v, 0, $limits[$k]);
}

// Honeypot: fingimos éxito para no dar pistas.
if (trim((string) ($_POST['website'] ?? '')) !== '') {
    header('Location: /contacto/?enviado=1#formulario', true, 303);
    exit;
}

$elapsed = time() - (int) ($_SESSION['aw_form_time'] ?? 0);
$tokenOk = hash_equals((string) ($_SESSION['aw_form_token'] ?? ''), (string) ($_POST['token'] ?? ''));

if (!$tokenOk || $elapsed < 3) {
    $errors['general'] = 'No se ha podido validar el envío. Revisa los datos y pulsa de nuevo «Enviar mensaje».';
}
if ($form['nombre'] === '') {
    $errors['nombre'] = 'Escribe tu nombre.';
}
if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Escribe un correo válido, por ejemplo nombre@empresa.es.';
}
if (mb_strlen($form['mensaje']) < 10) {
    $errors['mensaje'] = 'Cuéntame un poco más: con una o dos frases es suficiente.';
}
if (!isset($needs[$form['necesidad']])) {
    $form['necesidad'] = 'otro';
}
if (empty($_POST['consentimiento'])) {
    $errors['consentimiento'] = 'Marca la casilla para que pueda usar tus datos y responderte.';
}

if (!$errors && !$config) {
    $errors['general'] = "El formulario no está disponible ahora mismo. Escríbeme a $publicEmail.";
}

if (!$errors) {
    require __DIR__ . '/../phpmailer/PHPMailer.php';
    require __DIR__ . '/../phpmailer/SMTP.php';
    require __DIR__ . '/../phpmailer/Exception.php';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $config['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['smtp_user'];
        $mail->Password   = $config['smtp_pass'];
        $mail->SMTPSecure = ($config['smtp_secure'] ?? 'ssl') === 'tls'
            ? PHPMailer::ENCRYPTION_STARTTLS
            : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = (int) $config['smtp_port'];
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($config['from_email'], $config['from_name'] ?? 'Web AutomaWorks');
        $mail->addReplyTo($form['email'], $form['nombre']);
        $mail->addAddress($config['mail_to']);

        $mail->isHTML(false);
        $mail->Subject = 'AutomaWorks – ' . $needs[$form['necesidad']] . ' – ' . $form['nombre'];
        $mail->Body =
            "Nombre: {$form['nombre']}\n" .
            "Email: {$form['email']}\n" .
            "Empresa: " . ($form['empresa'] ?: '—') . "\n" .
            "Teléfono: " . ($form['telefono'] ?: '—') . "\n" .
            "Necesidad: {$needs[$form['necesidad']]}\n\n" .
            "Mensaje:\n{$form['mensaje']}\n\n" .
            "---\nEnviado desde automaworks.es/contacto el " . date('d/m/Y H:i');

        $mail->send();
        unset($_SESSION['aw_form_time'], $_SESSION['aw_form_token']);
        header('Location: /contacto/?enviado=1#formulario', true, 303);
        exit;
    } catch (Exception $e) {
        error_log('[automaworks contacto] ' . $mail->ErrorInfo);
        $errors['general'] = "No se ha podido enviar el mensaje. Escríbeme directamente a $publicEmail.";
    }
}

// Hay errores: nuevo token para el siguiente intento, conservando lo escrito.
$newToken();
$_SESSION['aw_form_time'] = time() - 3;
