<?php
http_response_code(404);
$page = [
    'title'       => 'Página no encontrada | AutomaWorks',
    'description' => 'La página que buscas no existe en automaworks.es.',
    'path'        => '/404',
    'robots'      => 'noindex,follow',
];
require __DIR__ . '/includes/header.php';
?>
<section class="wrap lost">
  <h1>Esta pieza no encaja en ningún sitio</h1>
  <p>La dirección que has escrito no existe o ha cambiado. Desde aquí puedes seguir por cualquiera de estas páginas.</p>
  <p class="hero__actions">
    <a class="btn btn--ink" href="/">Ir al inicio</a>
    <a class="btn btn--line" href="/soluciones/">Ver soluciones</a>
    <a class="btn btn--line" href="/proyectos/">Ver proyectos</a>
  </p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
