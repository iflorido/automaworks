<?php
/**
 * Cabecera común. Cada página define antes:
 *   $page = [
 *     'title'       => 'Título de la pestaña',
 *     'description' => 'Meta descripción',
 *     'path'        => '/soluciones/',
 *     'nav'         => 'soluciones' | 'proyectos' | 'contacto' | '',
 *     'jsonld'      => '...' (opcional),
 *   ];
 */
$site  = 'https://automaworks.es';
$title = $page['title'] ?? 'AutomaWorks — Software a medida, integraciones e IA para empresas';
$desc  = $page['description'] ?? '';
$path  = $page['path'] ?? '/';
$nav   = $page['nav'] ?? '';
$asset_v = '1.2.0';

if (!function_exists('aw_e')) {
    function aw_e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}
$current = function ($slug) use ($nav) { return $slug === $nav ? ' aria-current="page"' : ''; };
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= aw_e($title) ?></title>
  <meta name="description" content="<?= aw_e($desc) ?>">
  <meta name="robots" content="<?= aw_e($page['robots'] ?? 'index,follow') ?>">
  <meta name="theme-color" content="#1B2632">
  <link rel="canonical" href="<?= aw_e($site . $path) ?>">
  <link rel="icon" href="/awfavicon.ico" sizes="any">

  <meta property="og:type" content="website">
  <meta property="og:locale" content="es_ES">
  <meta property="og:site_name" content="AutomaWorks">
  <meta property="og:url" content="<?= aw_e($site . $path) ?>">
  <meta property="og:title" content="<?= aw_e($title) ?>">
  <meta property="og:description" content="<?= aw_e($desc) ?>">
  <meta property="og:image" content="<?= aw_e($site) ?>/social-share.jpg">
  <meta name="twitter:card" content="summary_large_image">

  <!-- Oxygen servida desde Bunny Fonts (réplica europea de Google Fonts, sin enviar la IP a Google) -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link rel="stylesheet" href="https://fonts.bunny.net/css?family=oxygen:300,400,700&display=swap">
  <link rel="stylesheet" href="/assets/css/site.css?v=<?= $asset_v ?>">
<?php if (!empty($page['jsonld'])): ?>
  <script type="application/ld+json"><?= $page['jsonld'] ?></script>
<?php endif; ?>
  <!-- Google Analytics (G-C0XB04YH7T) se carga desde /assets/js/cookies.js solo tras aceptar el banner. -->
</head>
<body>
<a class="skip" href="#contenido">Saltar al contenido</a>

<header class="site-header">
  <div class="wrap site-header__inner">
    <a class="brand" href="/" aria-label="AutomaWorks, inicio">
      <svg class="brand__mark" viewBox="0 0 20 20" aria-hidden="true">
        <rect x="0"  y="0"  width="12" height="9"  fill="#2C3B4D"/>
        <rect x="12" y="0"  width="8"  height="13" fill="#A35139"/>
        <rect x="0"  y="9"  width="12" height="11" fill="#FFB162"/>
        <rect x="12" y="13" width="8"  height="7"  fill="#1B2632"/>
      </svg>
      AutomaWorks
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="menu">Menú</button>

    <nav class="nav" id="menu" aria-label="Principal">
      <a href="/soluciones/"<?= $current('soluciones') ?>>Soluciones</a>
      <a href="/proyectos/"<?= $current('proyectos') ?>>Proyectos</a>
      <a href="/#quien">Quién hay detrás</a>
      <a class="btn btn--ink" href="/contacto/"<?= $current('contacto') ?>>Contacto</a>
    </nav>
  </div>
</header>

<main id="contenido">
