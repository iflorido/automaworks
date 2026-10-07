<?php
/**
 * Formulario de contacto — mismo sistema que iflorido.es (PHPMailer + honeypot +
 * control de tiempo), con dos diferencias:
 *  - Las credenciales SMTP viven en includes/config.php (fuera de git, no accesible por web).
 *  - Patrón POST/Redirect/GET para que recargar la página no reenvíe el mensaje.
 */
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

const AW_CONTACT_TOPICS = [
    'Software a medida',
    'Integraciones y APIs',
    'Automatización de procesos',
    'Inteligencia artificial',
    'Dolibarr ERP',
    'Consultoría o mantenimiento',
    'Otro tema',
];

function aw_contact_handle(): array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'httponly' => true,
            'secure'   => !empty($_SERVER['HTTPS']),
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    $state = ['sent' => false, 'error' => '', 'old' => []];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['_form'])) {
        $_SESSION['form_time'] = time();
        if (!empty($_SESSION['form_sent'])) {
            $state['sent'] = true;
            unset($_SESSION['form_sent']);
        }
        return $state;
    }

    $clean = static fn(string $k, int $max) =>
        mb_substr(trim(strip_tags((string) ($_POST[$k] ?? ''))), 0, $max);

    $old = [
        'nombre'   => $clean('nombre', 120),
        'email'    => $clean('email', 160),
        'empresa'  => $clean('empresa', 160),
        'tema'     => $clean('tema', 60),
        'mensaje'  => $clean('mensaje', 5000),
        'privacidad' => !empty($_POST['privacidad']),
    ];
    $state['old'] = $old;

    $elapsed = isset($_SESSION['form_time']) ? time() - (int) $_SESSION['form_time'] : 999;
    $_SESSION['form_time'] = time();

    // Honeypot relleno: fingimos éxito para no dar pistas al bot.
    if (!empty($_POST['website'])) {
        aw_contact_redirect_ok();
    }

    if ($elapsed < 3) {
        $state['error'] = 'El formulario se ha enviado demasiado rápido. Espera unos segundos y vuelve a enviarlo.';
        return $state;
    }

    // Límite sencillo: 3 envíos por sesión y hora.
    $now = time();
    $_SESSION['form_sends'] = array_values(array_filter(
        $_SESSION['form_sends'] ?? [],
        static fn($t) => $now - (int) $t < 3600
    ));
    if (count($_SESSION['form_sends']) >= 3) {
        $state['error'] = 'Has enviado varios mensajes en poco tiempo. Escribe directamente a '
            . AW_EMAIL_USER . '@' . AW_EMAIL_DOMAIN . '.';
        return $state;
    }

    if ($old['nombre'] === '' || $old['mensaje'] === '' || !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $state['error'] = 'Faltan datos: revisa tu nombre, un email válido y el mensaje.';
        return $state;
    }
    if (!$old['privacidad']) {
        $state['error'] = 'Para enviar el mensaje tienes que aceptar la política de privacidad.';
        return $state;
    }
    if (!in_array($old['tema'], AW_CONTACT_TOPICS, true)) {
        $old['tema'] = 'Otro tema';
    }

    $cfgFile = __DIR__ . '/config.php';
    $mailer  = __DIR__ . '/phpmailer/PHPMailer.php';
    if (!is_file($cfgFile) || !is_file($mailer)) {
        error_log('[automaworks] Falta includes/config.php o includes/phpmailer/');
        $state['error'] = 'El envío no está disponible ahora mismo. Escribe directamente a '
            . AW_EMAIL_USER . '@' . AW_EMAIL_DOMAIN . '.';
        return $state;
    }
    $cfg = require $cfgFile;
    require_once __DIR__ . '/phpmailer/PHPMailer.php';
    require_once __DIR__ . '/phpmailer/SMTP.php';
    require_once __DIR__ . '/phpmailer/Exception.php';

    $oneLine = static fn(string $s) => str_replace(["\r", "\n"], ' ', $s);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $cfg['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $cfg['smtp_user'];
        $mail->Password   = $cfg['smtp_pass'];
        $mail->SMTPSecure = ($cfg['smtp_secure'] ?? 'ssl') === 'tls'
            ? PHPMailer::ENCRYPTION_STARTTLS
            : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = (int) $cfg['smtp_port'];
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($cfg['from_email'], $cfg['from_name'] ?? 'Web AutomaWorks');
        $mail->addReplyTo($old['email'], $oneLine($old['nombre']));
        $mail->addAddress($cfg['to_email']);

        $mail->isHTML(false);
        $mail->Subject = $oneLine('AutomaWorks – ' . $old['tema'] . ' – ' . $old['nombre']);
        $mail->Body    = "Nombre: {$old['nombre']}\n"
            . "Email: {$old['email']}\n"
            . "Empresa: " . ($old['empresa'] ?: '—') . "\n"
            . "Tema: {$old['tema']}\n\n"
            . "Mensaje:\n{$old['mensaje']}\n";

        $mail->send();
        $_SESSION['form_sends'][] = $now;
        aw_contact_redirect_ok();
    } catch (MailException $ex) {
        error_log('[automaworks] Error SMTP: ' . $mail->ErrorInfo);
        $state['error'] = 'No se ha podido enviar el mensaje. Escribe directamente a '
            . AW_EMAIL_USER . '@' . AW_EMAIL_DOMAIN . '.';
    }

    return $state;
}

function aw_contact_redirect_ok(): void
{
    $_SESSION['form_sent'] = true;
    header('Location: /contacto/#formulario', true, 303);
    exit;
}
