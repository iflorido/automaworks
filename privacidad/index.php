<?php
$page = [
    'title'       => 'Privacidad y cookies | AutomaWorks',
    'description' => 'Cómo trata AutomaWorks los datos del formulario de contacto y del asistente, y qué cookies usa esta web.',
    'path'        => '/privacidad/',
    'robots'      => 'noindex,follow',
];
require __DIR__ . '/../includes/header.php';
?>
<header class="page-head">
  <div class="wrap"><h1>Privacidad y cookies</h1></div>
</header>

<div class="wrap">
  <article class="prose">
    <!-- REVISAR ANTES DE PUBLICAR: completa NIF y domicilio, y ajusta el apartado del asistente a lo que guarde realmente el contenedor. -->
    <h2>Responsable del tratamiento</h2>
    <p>Ignacio Florido Pérez (AutomaWorks), NIF [COMPLETAR], con domicilio en [COMPLETAR], El Puerto de Santa María (Cádiz). Contacto: contacto@automaworks.es.</p>

    <h2>Formulario de contacto</h2>
    <ul>
      <li>Datos: nombre, correo electrónico y, si los indicas, empresa, teléfono y el contenido del mensaje.</li>
      <li>Finalidad: responder a tu consulta y, si lo solicitas, preparar una propuesta.</li>
      <li>Legitimación: tu consentimiento, que puedes retirar en cualquier momento.</li>
      <li>Conservación: el tiempo necesario para atender la consulta y, si hay relación comercial, durante los plazos legales.</li>
    </ul>

    <h2>Asistente de inteligencia artificial</h2>
    <p>Las preguntas que escribes en el asistente se envían a un servidor propio de AutomaWorks, que consulta un modelo de lenguaje de un proveedor externo para generar la respuesta. Las conversaciones se registran para mejorar el servicio. El nombre y el correo solo se guardan si decides escribirlos. No introduzcas datos sensibles en el asistente.</p>

    <h2>Destinatarios</h2>
    <p>Los datos no se ceden a terceros salvo obligación legal. Se alojan en servidores situados en la Unión Europea. El proveedor del modelo de lenguaje actúa como encargado del tratamiento para generar las respuestas del asistente. Si aceptas las cookies de medición, Google trata datos de navegación como se explica en el apartado de cookies.</p>

    <h2>Tus derechos</h2>
    <p>Puedes ejercer los derechos de acceso, rectificación, supresión, oposición, limitación y portabilidad escribiendo a contacto@automaworks.es. Si consideras que no se han atendido, puedes reclamar ante la Agencia Española de Protección de Datos (aepd.es).</p>

    <h2 id="cookies">Cookies</h2>
    <p>Esta web no usa cookies publicitarias. Usa estos dos tipos de cookies:</p>
    <ul>
      <li><strong>Técnica (siempre activa).</strong> La página de contacto crea una cookie de sesión, <code>aw_contacto</code>, necesaria para proteger el formulario frente a envíos automáticos. Se borra al cerrar el navegador.</li>
      <li><strong>De medición (solo si la aceptas).</strong> Google Analytics, de Google Ireland Ltd., instala las cookies <code>_ga</code> y <code>_ga_*</code> para contar visitas y saber qué páginas se leen. Duran hasta dos años. Google puede transferir datos a Estados Unidos dentro del Marco de Privacidad de Datos UE-EE.UU. Más información en la <a class="link" href="https://policies.google.com/privacy?hl=es" rel="noopener">política de privacidad de Google</a>.</li>
    </ul>
    <p>Tu decisión se guarda en tu navegador durante 12 meses. Puedes cambiarla cuando quieras: <button type="button" class="link footer-link-btn" data-cookie-settings>configurar cookies</button>. Si retiras el consentimiento, Google Analytics deja de cargarse y se borran sus cookies.</p>
    <p>Las fuentes tipográficas se sirven desde Bunny Fonts, un servicio europeo que no instala cookies.</p>
  </article>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
