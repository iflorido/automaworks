<?php
// ─── Configuración del formulario ───
$formSent    = false;
$formError   = false;
$formMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_form'])) {
    $nombre  = trim(strip_tags($_POST['nombre']  ?? ''));
    $email   = trim(strip_tags($_POST['email']   ?? ''));
    $interes = trim(strip_tags($_POST['interes']  ?? ''));
    $mensaje = trim(strip_tags($_POST['mensaje']  ?? ''));

    if ($nombre && filter_var($email, FILTER_VALIDATE_EMAIL) && $mensaje) {
        $to      = 'iflorido@gmail.com';
        $subject = "Portfolio – $interes – $nombre";
        $body    = "Nombre: $nombre\nEmail: $email\nInterés: $interes\n\nMensaje:\n$mensaje";
        $headers = "From: noreply@iflorido.es\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";

        if (mail($to, $subject, $body, $headers)) {
            $formSent    = true;
            $formMessage = 'Mensaje enviado. Te responderé lo antes posible.';
        } else {
            $formError   = true;
            $formMessage = 'Error al enviar. Puedes escribirme directamente a iflorido@gmail.com';
        }
    } else {
        $formError   = true;
        $formMessage = 'Por favor, completa todos los campos obligatorios.';
    }
}
?>
<!doctype html>
<html lang="es" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ignacio Florido — Desarrollador Backend Python · Desarrollo Web · Automatización</title>
  <meta name="description" content="Portfolio profesional de Ignacio Florido. Desarrollo backend con Python, Django y FastAPI, integraciones, automatización, Docker, VPS, Linux y producto web.">
  <meta name="robots" content="index,follow">
  <meta name="author" content="Ignacio Florido">
  <meta name="theme-color" content="#0a0f1a">
  <link rel="canonical" href="https://cv.iflorido.es/">

  <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png">
  <link rel="icon" type="image/png" sizes="192x192" href="assets/favicon-192.png">
   <link rel="icon" href="assets/favicon-32.ico">

  <link rel="apple-touch-icon" href="assets/apple-touch-icon.png">

  <meta property="og:type" content="website">
  <meta property="og:locale" content="es_ES">
  <meta property="og:url" content="https://cv.iflorido.es/">
  <meta property="og:title" content="Ignacio Florido — Desarrollador Backend Python · Integraciones · Infraestructura">
  <meta property="og:description" content="+20 años construyendo soluciones digitales. Backend Python, automatización, integraciones, Docker y despliegue en producción.">
  <meta property="og:image" content="assets/og-ignacio-florido.jpg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Ignacio Florido — Backend Python · Integraciones · Infraestructura">
  <meta name="twitter:description" content="+20 años construyendo soluciones digitales. Backend Python, automatización, integraciones y despliegue.">
  <meta name="twitter:image" content="assets/og-ignacio-florido.jpg">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,300;12..96,500;12..96,700;12..96,800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,400&display=swap" rel="stylesheet">
  <link href="assets/style.css" rel="stylesheet">

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "Ignacio Florido",
    "url": "https://cv.iflorido.es/",
    "jobTitle": "Desarrollador Backend Python, Integraciones e Infraestructura",
    "sameAs": [
      "https://www.linkedin.com/in/ignacio-florido/",
      "https://github.com/iflorido",
      "https://automaworks.es/"
    ],
    "email": "mailto:iflorido@gmail.com",
    "knowsAbout": [
      "Python","Django","FastAPI","Flask","PostgreSQL","Docker",
      "WordPress","PrestaShop","Plesk","WHM","Linux","Automatización","Integraciones"
    ]
  }
  </script>
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-FFCQ24M09Z"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-FFCQ24M09Z');
</script>
</head>
<body>

  <!-- ════════════════════════════════════════════════════════════════
       NAV
       ════════════════════════════════════════════════════════════ -->
  <nav class="nav" id="nav">
    <div class="nav__inner">
      <a class="nav__brand" href="#top">
        <span class="nav__name">Ignacio Florido</span>
        <span class="nav__role">Backend Python · Integraciones</span>
      </a>

      <button class="nav__toggle" id="navToggle" type="button" aria-label="Abrir navegación" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>

      <div class="nav__menu" id="navMenu">
        <ul class="nav__links">
          <li><a href="#valor">Valor</a></li>
          <li><a href="#proyectos">Proyectos</a></li>
          <li><a href="#experiencia">Trayectoria</a></li>
          <li><a href="#stack">Stack</a></li>
          <li><a href="#contacto">Contacto</a></li>
        </ul>
        <div class="nav__actions">
          <button id="themeToggle" class="btn btn--icon" type="button" aria-label="Cambiar tema">
            <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
            <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
          </button>
          <a class="btn btn--ghost" href="https://www.linkedin.com/in/ignacio-florido/" target="_blank" rel="noopener">LinkedIn</a>
          <a class="btn btn--primary" href="#contacto">Contactar</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- ════════════════════════════════════════════════════════════════
       HERO
       ════════════════════════════════════════════════════════════ -->
  <header id="top" class="hero">
    <div class="container hero__grid">
      <div class="hero__content">
        <div class="hero__badge reveal" data-delay="0">
          <span class="pulse"></span>
          Disponible para nuevos retos
        </div>
        <h1 class="hero__title reveal" data-delay="1">
          Desarrollo backend con Python,<br>
          <span class="hero__accent">visión de producto</span><br>
          e infraestructura real.
        </h1>
        <p class="hero__lead reveal" data-delay="2">
          Más de 20 años construyendo soluciones digitales. Hoy enfocado en
          <strong>backend con Python</strong>, <strong>Django</strong>, <strong>FastAPI</strong>,
          automatización y APIs — con base sólida en <strong>e-commerce</strong>,
          despliegues con <strong>Docker</strong>, Linux, VPS y paneles de producción.
        </p>

        <div class="hero__cta reveal" data-delay="3">
          <a href="#proyectos" class="btn btn--primary">Ver proyectos</a>
          <a href="assets/Ignacio_Florido_cv.pdf" class="btn btn--ghost" target="_blank" rel="noopener">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            Descargar CV
          </a>
          <a href="https://github.com/iflorido" class="btn btn--ghost" target="_blank" rel="noopener">GitHub</a>
        </div>

        <div class="hero__tags reveal" data-delay="4">
          <span class="tag">Python</span>
          <span class="tag">Django</span>
          <span class="tag">FastAPI</span>
          <span class="tag">PostgreSQL</span>
          <span class="tag">Docker</span>
          <span class="tag">Linux / VPS</span>
        </div>
      </div>

      <aside class="hero__aside reveal" data-delay="2">
        <div class="stat-card">
          <div class="stat-card__label">Posicionamiento</div>
          <h2 class="stat-card__title">Senior digital builder → especialización en backend Python e integración</h2>
          <p class="stat-card__desc">Experiencia senior en ejecución digital, desarrollo web, operación técnica, automatización y despliegue.</p>
          <div class="stat-card__grid">
            <div class="stat-block">
              <div class="stat-block__number">20<span>+</span></div>
              <div class="stat-block__label">años en proyectos<br>web y digitales</div>
            </div>
            <div class="stat-block">
              <div class="stat-block__number">6<span>+</span></div>
              <div class="stat-block__label">proyectos Python<br>en producción</div>
            </div>
          </div>
          <div class="stat-card__focus">
            <div class="stat-card__focus-label">Enfoque actual</div>
            <p>Backend, integraciones, automatización, producto web, infraestructura y despliegue en producción.</p>
          </div>
        </div>
      </aside>
    </div>
    <div class="hero__gradient" aria-hidden="true"></div>
  </header>

  <main>

    <!-- ══════════════ VALOR ══════════════ -->
    <section id="valor" class="section">
      <div class="container">
        <div class="section__header reveal">
          <span class="eyebrow">Valor diferencial</span>
          <h2 class="section__title">Qué aporto a un equipo técnico o a un producto digital</h2>
        </div>
        <div class="grid grid--3 mt-lg">
          <article class="card reveal" data-delay="0">
            <div class="card__icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 18l2-2-2-2M8 18l-2-2 2-2M14 4l-4 16"/></svg>
            </div>
            <h3 class="card__title">Backend Python con aplicación real</h3>
            <p class="card__text">Aplicaciones con Django, FastAPI y Flask integrando bases de datos, APIs, lógica de negocio, automatización y despliegue. Código que llega a producción.</p>
          </article>
          <article class="card reveal" data-delay="1">
            <div class="card__icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
            </div>
            <h3 class="card__title">Visión completa: negocio, UX e infraestructura</h3>
            <p class="card__text">Entiendo SEO, e-commerce, entornos Linux, VPS, Docker, paneles de hosting y operación real. No me limito al código.</p>
          </article>
          <article class="card reveal" data-delay="2">
            <div class="card__icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <h3 class="card__title">Autonomía y capacidad de entrega</h3>
            <p class="card__text">Trayectoria ejecutando proyectos de principio a fin, con criterio técnico, capacidad resolutiva y foco en el resultado final.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- ══════════════ PROYECTOS ══════════════ -->
    <section id="proyectos" class="section section--alt">
      <div class="container">
        <div class="section__header reveal">
          <span class="eyebrow">Proyectos seleccionados</span>
          <h2 class="section__title">Trabajo real: producto, backend, automatización</h2>
          <p class="section__subtitle">Proyectos propios en producción que demuestran capacidad de construir soluciones completas.</p>
        </div>

        <div class="grid grid--3 mt-lg">
                    <!-- MapGasolina -->
          <article class="project reveal" data-delay="0">
            <div class="project__img-wrap">
              <img class="project__img" src="assets/proyectos/mapagasolina.jpg" alt="MapaGasolina – Plataforma de visualización de precios de gasolina" loading="lazy">
            </div>
            <div class="project__body">
              <div class="project__meta">2026 · FasAPI · SQL · JavaScript</div>
              <h3 class="project__title">MapaGasolina</h3>
              <p class="project__desc">Plataforma para visualización de precios de gasolina en tiempo real. Proyecto orientado a operaciones con estructura modular y producto web real.</p>
              <a class="btn btn--ghost btn--sm" href="https://mapagasolina.com/" target="_blank" rel="noopener">Ver proyecto →</a>
            </div>
          </article>
          <!-- OfiGest -->
          <article class="project reveal" data-delay="0">
            <div class="project__img-wrap">
              <img class="project__img" src="assets/proyectos/ofigest.jpg" alt="OfiGest – Plataforma de gestión de alquiler de oficinas" loading="lazy">
            </div>
            <div class="project__body">
              <div class="project__meta">2026 · Django · PostgreSQL · JavaScript</div>
              <h3 class="project__title">OfiGest</h3>
              <p class="project__desc">Plataforma para gestión del alquiler de oficinas y espacios. Proyecto orientado a operaciones con estructura modular y producto web real.</p>
              <a class="btn btn--ghost btn--sm" href="https://ofigest.automaworks.es/" target="_blank" rel="noopener">Ver proyecto →</a>
            </div>
          </article>

          <!-- NavControl -->
          <article class="project reveal" data-delay="1">
            <div class="project__img-wrap">
              <img class="project__img" src="assets/proyectos/navcontrol.jpg" alt="NavControl – Sistema de localización de flotas" loading="lazy">
            </div>
            <div class="project__body">
              <div class="project__meta">2026 · Django · PostGIS · Flutter</div>
              <h3 class="project__title">NavControl</h3>
              <p class="project__desc">Localización de flotas, rutas y avisos en tiempo real. Backend, cartografía y app móvil conectados a una necesidad concreta.</p>
              <a class="btn btn--ghost btn--sm" href="https://navcontrol.automaworks.es/" target="_blank" rel="noopener">Ver proyecto →</a>
            </div>
          </article>

          <!-- MercaAPI -->
          <article class="project reveal" data-delay="0">
            <div class="project__img-wrap">
              <img class="project__img" src="assets/proyectos/mercaapi.jpg" alt="MercaAPI – Backend para consumo de APIs" loading="lazy">
            </div>
            <div class="project__body">
              <div class="project__meta">2026 · FastAPI · Docker · CI/CD</div>
              <h3 class="project__title">MercaAPI</h3>
              <p class="project__desc">Backend en Python preparado para web y app móvil, con consumo de datos en tiempo real. Despliegue automatizado con GitHub Actions + Docker Hub + Watchtower.</p>
              <a class="btn btn--ghost btn--sm" href="https://mercaapi.automaworks.es/" target="_blank" rel="noopener">Ver proyecto →</a>
            </div>
          </article>

          <!-- Dolibarr Tools -->
          <article class="project reveal" data-delay="1">
            <div class="project__img-wrap">
              <img class="project__img" src="assets/proyectos/calculadora.jpg" alt="Calculadora de préstamos hipotecarios" loading="lazy">
            </div>
            <div class="project__body">
              <div class="project__meta">2026 · HTML JavaScript · Automatización</div>
              <h3 class="project__title">Calculadora de préstamos hipotecarios</h3>
              <p class="project__desc"> Calculadora para estimar cuotas y costes de préstamos hipotecarios.</p>
              <a class="btn btn--ghost btn--sm" href="https://automaworks.es/calculadora/" target="_blank" rel="noopener">Ver proyecto →</a>
            </div>
          </article>
          <article class="project reveal" data-delay="1">
            <div class="project__img-wrap">
              <img class="project__img" src="assets/proyectos/dolibarrtools.jpg" alt="Dolibarr Tools – Suite de automatización ERP" loading="lazy">
            </div>
            <div class="project__body">
              <div class="project__meta">2026 · Flask · ERP · Automatización</div>
              <h3 class="project__title">Dolibarr Tools</h3>
              <p class="project__desc">Suite de automatización para Dolibarr ERP: sincronización avanzada, módulos personalizados y scripts Python para optimizar flujos empresariales.</p>
              <a class="btn btn--ghost btn--sm" href="https://dolibarrtools.automaworks.es/" target="_blank" rel="noopener">Ver proyecto →</a>
            </div>
          </article>
        </div>

        <!-- Más proyectos -->
        <div class="projects-extra mt-lg reveal">
          <h3 class="projects-extra__title">Más proyectos en producción</h3>
          <div class="grid grid--3">
            <a href="https://peliculas.automaworks.es/" class="mini-project" target="_blank" rel="noopener">
              <span class="mini-project__tag">NLP · Flask</span>
              <span class="mini-project__name">Netflix Recommender</span>
              <span class="mini-project__desc">Búsqueda semántica con IA para recomendación de contenido</span>
            </a>
            <a href="https://mapagasolina.com" class="mini-project" target="_blank" rel="noopener">
              <span class="mini-project__tag">Dashboard · API</span>
              <span class="mini-project__name">Dashboard Gasolina</span>
              <span class="mini-project__desc">Precios de gasolina en España con datos del Ministerio</span>
            </a>
            <a href="https://dashfinanciero.automaworks.es" class="mini-project" target="_blank" rel="noopener">
              <span class="mini-project__tag">PySpark · Big Data</span>
              <span class="mini-project__name">Fraud Detection</span>
              <span class="mini-project__desc">Análisis de fraudes bancarios con Apache Spark</span>
            </a>
          </div>
        </div>

        <div class="card card--highlight mt-lg reveal">
          <p><strong>También fuera de Python:</strong> amplia experiencia en WordPress, WooCommerce, PrestaShop, webs corporativas, e-commerce y mantenimiento técnico. Entiendo el ciclo completo de una solución digital — desde la arquitectura hasta la producción y el soporte.</p>
        </div>
        <!-- Trabajos web y e-commerce -->
        <div class="projects-extra mt-lg reveal">
          <h3 class="projects-extra__title">Selección de trabajos web y e-commerce</h3>
          <div class="grid grid--4">
            <a href="https://www.nomadassurf.com/" class="mini-project" target="_blank" rel="noopener">
              <span class="mini-project__tag">PrestaShop</span>
              <span class="mini-project__name">Nómadas Surf</span>
              <span class="mini-project__desc">Tienda online para distribuidora de surf a nivel nacional</span>
            </a>
            <a href="https://www.amagidesarrollos.com/" class="mini-project" target="_blank" rel="noopener">
              <span class="mini-project__tag">WordPress · Elementor</span>
              <span class="mini-project__name">AMAGI Desarrollos</span>
              <span class="mini-project__desc">Web corporativa para promotora inmobiliaria</span>
            </a>
            <a href="http://vivero.ceeicadiz.com/" class="mini-project" target="_blank" rel="noopener">
              <span class="mini-project__tag">PHP · MySQL · A medida</span>
              <span class="mini-project__name">Vivero CEEI Cádiz</span>
              <span class="mini-project__desc">Gestión de oficinas con planos interactivos de ocupación</span>
            </a>
            <a href="https://catemo.es" class="mini-project" target="_blank" rel="noopener">
              <span class="mini-project__tag">WordPress · Kit Digital</span>
              <span class="mini-project__name">Catemo</span>
              <span class="mini-project__desc">Web corporativa con justificación Kit Digital</span>
            </a>
            <a href="https://www.adhararesearch.com" class="mini-project" target="_blank" rel="noopener">
              <span class="mini-project__tag">WordPress</span>
              <span class="mini-project__name">Adhara Research</span>
              <span class="mini-project__desc">Web para agencia de investigación de mercados</span>
            </a>
            <a href="https://www.bodente.com/" class="mini-project" target="_blank" rel="noopener">
              <span class="mini-project__tag">WordPress</span>
              <span class="mini-project__name">Bodente Madrid</span>
              <span class="mini-project__desc">Web para restaurante con reservas y carta</span>
            </a>
            <a href="http://mariabarrerasuelopelvico.com" class="mini-project" target="_blank" rel="noopener">
              <span class="mini-project__tag">WordPress · Kit Digital</span>
              <span class="mini-project__name">María Barrera Fisio</span>
              <span class="mini-project__desc">Web profesional para clínica de fisioterapia</span>
            </a>
            <a href="https://www.thewavedistrict.com/" class="mini-project" target="_blank" rel="noopener">
              <span class="mini-project__tag">WordPress · WooCommerce</span>
              <span class="mini-project__name">The Wave District</span>
              <span class="mini-project__desc">Tienda online de material deportivo</span>
            </a>
          </div>
        </div>
         <!-- Trabajos web y e-commerce -->
      </div>
    </section>

    <!-- ══════════════ EXPERIENCIA ══════════════ -->
    <section id="experiencia" class="section">
      <div class="container">
        <div class="exp__grid">
          <div class="exp__intro reveal">
            <span class="eyebrow">Trayectoria</span>
            <h2 class="section__title">De diseño web a backend, integración e infraestructura</h2>
            <p class="section__subtitle">No es una suma de trabajos inconexos. Es una progresión: diseño y desarrollo → proyectos digitales completos → especialización técnica en Python, automatización y despliegue.</p>
          </div>

          <div class="timeline">
            <article class="timeline__item reveal" data-delay="0">
              <div class="timeline__dot"></div>
              <div class="timeline__content">
                <div class="timeline__header">
                  <h3 class="timeline__company">Servicebox SL</h3>
                  <span class="timeline__date">2022 — Actualidad</span>
                </div>
                <div class="timeline__role">Desarrollo web y proyectos digitales</div>
                <p class="timeline__desc">Webs corporativas, e-commerce y proyectos para clientes. WordPress, Elementor, PrestaShop, integraciones, SEO on-page y puesta en producción.</p>
              </div>
            </article>

            <article class="timeline__item reveal" data-delay="1">
              <div class="timeline__dot"></div>
              <div class="timeline__content">
                <div class="timeline__header">
                  <h3 class="timeline__company">Autónomo</h3>
                  <span class="timeline__date">2004 — 2022</span>
                </div>
                <div class="timeline__role">Diseño, desarrollo y producción digital</div>
                <p class="timeline__desc">Proyectos de diseño gráfico, desarrollo web, fotografía y vídeo para múltiples sectores. Etapa clave para construir autonomía y capacidad de ejecución integral.</p>
              </div>
            </article>

            <article class="timeline__item reveal" data-delay="2">
              <div class="timeline__dot"></div>
              <div class="timeline__content">
                <div class="timeline__header">
                  <h3 class="timeline__company">CEEI Bahía de Cádiz</h3>
                  <span class="timeline__date">2010 — 2021</span>
                </div>
                <div class="timeline__role">Diseño, desarrollo y sistemas</div>
                <p class="timeline__desc">Backend y frontend, identidades de proyectos y administración técnica de entornos web. Consolidación en servidores, operación y soporte técnico.</p>
              </div>
            </article>

            <article class="timeline__item reveal" data-delay="3">
              <div class="timeline__dot"></div>
              <div class="timeline__content">
                <div class="timeline__header">
                  <h3 class="timeline__company">SmartMedia Factory <span class="timeline__location">Finlandia</span></h3>
                  <span class="timeline__date">2008 — 2010</span>
                </div>
                <div class="timeline__role">Desarrollo multimedia para TV</div>
                <p class="timeline__desc">Aplicaciones para televisión, participación de usuarios y maquetación de sitios y plataformas web.</p>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════ STACK ══════════════ -->
    <section id="stack" class="section section--alt">
      <div class="container">
        <div class="section__header reveal">
          <span class="eyebrow">Stack y capacidades</span>
          <h2 class="section__title">Tecnologías con las que construyo, despliego y mantengo</h2>
        </div>

        <div class="stack-panel mt-lg reveal">
          <div class="grid grid--4">
            <div class="stack-group">
              <h3 class="stack-group__title">Backend y datos</h3>
              <p class="stack-group__list">Python, Django, FastAPI, Flask, PostgreSQL, MySQL, SQLite, Pandas, PySpark, APIs REST.</p>
            </div>
            <div class="stack-group">
              <h3 class="stack-group__title">Web y e-commerce</h3>
              <p class="stack-group__list">HTML, CSS, JavaScript, Bootstrap, WordPress, WooCommerce, Elementor, PrestaShop, SEO.</p>
            </div>
            <div class="stack-group">
              <h3 class="stack-group__title">Infraestructura</h3>
              <p class="stack-group__list">Linux, VPS, Nginx, Apache, Docker, GitHub Actions, Watchtower, Plesk, WHM, cPanel, SSL.</p>
            </div>
            <div class="stack-group">
              <h3 class="stack-group__title">Producto y operación</h3>
              <p class="stack-group__list">Automatización, integraciones CRM/ERP, Dolibarr, gestión de proyectos, visión de negocio.</p>
            </div>
          </div>
          <hr class="divider">
          <div class="stack-tags">
            <span class="tag">Python</span>
            <span class="tag">Django</span>
            <span class="tag">FastAPI</span>
            <span class="tag">Flask</span>
            <span class="tag">PostgreSQL</span>
            <span class="tag">Docker</span>
            <span class="tag">Linux</span>
            <span class="tag">VPS</span>
            <span class="tag">Plesk</span>
            <span class="tag">WHM</span>
            <span class="tag">WordPress</span>
            <span class="tag">WooCommerce</span>
            <span class="tag">PrestaShop</span>
            <span class="tag">Dolibarr</span>
            <span class="tag">GitHub Actions</span>
            <span class="tag">Nginx</span>
            <span class="tag">Pandas</span>
            <span class="tag">PySpark</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════ FORMACIÓN ══════════════ -->
    <section id="formacion" class="section">
      <div class="container">
        <div class="grid grid--2">
          <article class="card reveal" data-delay="0">
            <span class="eyebrow">Formación destacada</span>
            <h2 class="card__title card__title--lg">Máster Avanzado en Programación en Python</h2>
            <p class="card__meta">Escuela Internacional Posgrado · 2022 — 2024</p>
            <p class="card__text">Python, Django, Flask, APIs REST, PostgreSQL, Docker, Big Data, PySpark, Machine Learning, Deep Learning y desarrollo seguro. Proyecto final calificado con 9.5.</p>
          </article>
          <article class="card reveal" data-delay="1">
            <span class="eyebrow">Base complementaria</span>
            <h2 class="card__title card__title--lg">Diseño y artes visuales</h2>
            <p class="card__meta">Escuela de Artes Visuales · 2002 — 2004</p>
            <p class="card__text">Base creativa con impacto práctico: productos más claros, interfaces con criterio y soluciones que el usuario final entiende y valora.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- ══════════════ CONTACTO ══════════════ -->
    <section id="contacto" class="section section--contact">
      <div class="container">
        <div class="contact-panel reveal">
          <div class="contact__grid">
            <div class="contact__info">
              <span class="eyebrow">Contacto</span>
              <h2 class="section__title">Busco un equipo donde aportar autonomía, criterio técnico y capacidad de entrega.</h2>
              <p class="section__subtitle">Interesado en posiciones de backend Python, integraciones, automatización, producto web y perfiles técnicos con visión amplia.</p>
              <div class="contact__links">
                <a class="contact__link" href="mailto:iflorido@gmail.com">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                  iflorido@gmail.com
                </a>
                <a class="contact__link" href="https://www.linkedin.com/in/ignacio-florido/" target="_blank" rel="noopener">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-4 0v7h-4v-7a6 6 0 016-6zM2 9h4v12H2zM4 6a2 2 0 100-4 2 2 0 000 4z"/></svg>
                  LinkedIn
                </a>
                <a class="contact__link" href="https://github.com/iflorido" target="_blank" rel="noopener">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/></svg>
                  GitHub
                </a>
                <a class="contact__link" href="https://automaworks.es/" target="_blank" rel="noopener">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                  automaworks.es
                </a>
              </div>
            </div>

            <div class="contact__form-wrap">
              <?php if ($formSent): ?>
                <div class="form-feedback form-feedback--ok">
                  <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  <p><?= htmlspecialchars($formMessage) ?></p>
                </div>
              <?php elseif ($formError): ?>
                <div class="form-feedback form-feedback--err">
                  <p><?= htmlspecialchars($formMessage) ?></p>
                </div>
              <?php endif; ?>

              <form class="form" method="POST" action="#contacto">
                <input type="hidden" name="_form" value="1">
                <div class="form__row">
                  <div class="form__group">
                    <label class="form__label" for="nombre">Nombre *</label>
                    <input class="form__input" type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
                  </div>
                  <div class="form__group">
                    <label class="form__label" for="email">Email *</label>
                    <input class="form__input" type="email" id="email" name="email" placeholder="tu@email.com" required>
                  </div>
                </div>
                <div class="form__group">
                  <label class="form__label" for="interes">Interés</label>
                  <select class="form__input form__select" id="interes" name="interes">
                    <option>Backend Python</option>
                    <option>Desarrollo web</option>
                    <option>Automatización e integraciones</option>
                    <option>Infraestructura y despliegue</option>
                    <option>Otro</option>
                  </select>
                </div>
                <div class="form__group">
                  <label class="form__label" for="mensaje">Mensaje *</label>
                  <textarea class="form__input" id="mensaje" name="mensaje" rows="5" placeholder="Cuéntame brevemente la oportunidad o proyecto" required></textarea>
                </div>
                <button type="submit" class="btn btn--primary btn--full">Enviar mensaje</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- ════════════════════════════════════════════════════════════════
       FOOTER
       ════════════════════════════════════════════════════════════ -->
  <footer class="footer">
    <div class="container footer__inner">
      <div>
        <strong>Ignacio Florido</strong><br>
        <span class="footer__role">Ignacio Florido — Desarrollador Backend Python · Desarrollo Web · Automatización</span>
      </div>
      <div class="footer__right">
        <span class="footer__copy">© <?= date('Y') ?></span>
      </div>
    </div>
  </footer>

  <script src="assets/main.js"></script>
</body>
</html>
