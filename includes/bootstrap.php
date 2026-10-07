<?php
/**
 * AutomaWorks — arranque común de todas las páginas.
 * Este directorio está bloqueado desde el navegador (.htaccess).
 */
declare(strict_types=1);

const AW_ROOT          = __DIR__ . '/..';
const AW_URL           = 'https://automaworks.es';
const AW_EMAIL_USER    = 'contacto';
const AW_EMAIL_DOMAIN  = 'automaworks.es';
const AW_GA_ID         = 'G-C0XB04YH7T';
// Endpoint del contenedor del asistente (pendiente de crear en el VPS).
// Mismo contrato que cvchat: POST {messages, session_id, name, email} -> {reply}
const AW_ASSISTANT_API = 'https://asistente.automaworks.es/chat';

/** Escapa texto para HTML. */
function e(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

/** Ruta de un recurso con versión por fecha de modificación (rompe caché al editar). */
function asset(string $path): string
{
    $file = AW_ROOT . $path;
    $v = is_file($file) ? (string) filemtime($file) : '1';
    return $path . '?v=' . $v;
}

/** Enlace de email ofuscado; aw.js lo completa en el navegador. */
function aw_email_link(string $class = ''): string
{
    return '<a class="js-email ' . e($class) . '" href="/contacto/" data-user="' . AW_EMAIL_USER
        . '" data-domain="' . AW_EMAIL_DOMAIN . '"><span>' . AW_EMAIL_USER
        . ' (arroba) ' . AW_EMAIL_DOMAIN . '</span></a>';
}

require __DIR__ . '/projects.php';
