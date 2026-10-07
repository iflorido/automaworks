<?php
/**
 * <head> común. Espera $page = [
 *   'title' => string, 'description' => string, 'path' => '/ruta/',
 *   'nav' => 'inicio'|'soluciones'|'proyectos'|'contacto'|'',
 *   'robots' => 'index,follow' (opcional), 'jsonld' => string (opcional)
 * ]
 */
$canonical = AW_URL . $page['path'];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($page['title']) ?></title>
  <meta name="description" content="<?= e($page['description']) ?>">
  <meta name="robots" content="<?= e($page['robots'] ?? 'index,follow') ?>">
  <link rel="canonical" href="<?= e($canonical) ?>">
  <meta name="theme-color" content="#1B2632">

  <meta property="og:type" content="website">
  <meta property="og:locale" content="es_ES">
  <meta property="og:site_name" content="AutomaWorks">
  <meta property="og:url" content="<?= e($canonical) ?>">
  <meta property="og:title" content="<?= e($page['title']) ?>">
  <meta property="og:description" content="<?= e($page['description']) ?>">
  <meta property="og:image" content="<?= AW_URL ?>/social-share.jpg">
  <meta name="twitter:card" content="summary_large_image">

  <link rel="icon" href="/awfavicon.ico" type="image/x-icon">
  <link rel="preload" href="/assets/fonts/archivo-var.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="<?= asset('/assets/css/aw.css') ?>">
<?php if (!empty($page['jsonld'])): ?>
  <script type="application/ld+json"><?= $page['jsonld'] ?></script>
<?php endif; ?>
  <script src="<?= asset('/assets/js/aw.js') ?>" defer data-ga="<?= AW_GA_ID ?>"></script>
</head>
<body class="page-<?= e($page['nav'] ?: 'otra') ?>">
<a class="skip" href="#contenido">Saltar al contenido</a>
<header class="site-header">
  <div class="site-header__inner">
    <a class="wordmark" href="/" <?= $page['nav'] === 'inicio' ? 'aria-current="page"' : '' ?>>AutomaWorks</a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="nav-principal">Menú</button>
    <nav class="nav" id="nav-principal" aria-label="Principal">
<?php foreach (['soluciones' => 'Soluciones', 'proyectos' => 'Proyectos', 'contacto' => 'Contacto'] as $slug => $label): ?>
      <a class="nav__link" href="/<?= $slug ?>/"<?= $page['nav'] === $slug ? ' aria-current="page"' : '' ?>><?= $label ?></a>
<?php endforeach; ?>
    </nav>
  </div>
</header>
<main id="contenido">
