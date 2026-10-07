<?php
/**
 * Copia este archivo como includes/config.php y rellena los datos.
 * config.php está en .gitignore y el .htaccess bloquea el acceso web a /includes/.
 * No pongas nunca credenciales directamente en los archivos de las páginas.
 */
return [
    'smtp_host'    => 'mail.automaworks.es',   // Postfix del VPS (Plesk)
    'smtp_port'    => 465,
    'smtp_secure'  => 'ssl',                   // 'ssl' para 465, 'tls' para 587
    'smtp_user'    => 'contacto@automaworks.es',
    'smtp_pass'    => 'CAMBIAR',

    'from_email'   => 'contacto@automaworks.es',
    'from_name'    => 'Web AutomaWorks',
    'mail_to'      => 'iflorido@gmail.com',     // dónde recibes los mensajes
    'public_email' => 'contacto@automaworks.es' // el que se muestra si falla el envío
];
