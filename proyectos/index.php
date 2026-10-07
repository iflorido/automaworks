<?php
$apps = [
    ['AppFincas', 'https://appfincas.com', 'appfincas',
     'Gestión de comunidades de propietarios con cuentas transparentes, remesas SEPA, votaciones online y comunicación directa con cada vecino.',
     'Django, PostgreSQL, HTMX, Docker'],
    ['AutoRent', 'https://autorent.automaworks.es/', 'autorent',
     'Alquiler de vehículos con reservas, contratos, pagos e ingesta de posiciones GPS mediante tareas en segundo plano.',
     'Django, Celery, PostgreSQL, React'],
    ['NavControl', 'https://navcontrol.automaworks.es/', 'navcontrol',
     'Localización de flotas, rutas y avisos en tiempo real. Backend, cartografía y aplicación móvil conectados.',
     'Django, PostGIS, Flutter'],
    ['El Precio de tu Casa', 'https://elpreciodetucasa.es/', 'elpreciodetucasa',
     'Valorador automático de viviendas construido solo con datos públicos: Catastro, valor tasado del Ministerio e INE.',
     'FastAPI, PostgreSQL con PostGIS, React'],
    ['MapaElectroCarga', 'https://mapaelectrocarga.com', 'mapaelectrocarga',
     'Mapa de más de 12.000 puntos de recarga para coches eléctricos en España, con conectores, potencia y operador.',
     'React, FastAPI, SQL'],
    ['MapaGasolina', 'https://mapagasolina.com/', 'mapagasolina',
     'Precios de los carburantes en España actualizados con los datos del Ministerio, con filtros por zona y combustible.',
     'FastAPI, SQL, JavaScript'],
    ['OfiGest', 'https://ofigest.automaworks.es/', 'ofigest',
     'Gestión del alquiler de oficinas, plazas de aparcamiento y trasteros, con contratos y ocupación.',
     'Django, PostgreSQL, JavaScript'],
    ['Dolibarr Tools', 'https://dolibarrtools.automaworks.es/', 'dolibarrtools',
     'Módulos y utilidades para Dolibarr ERP: sincronización con tiendas online, control horario y bolsas de horas.',
     'PHP, Flask, API de Dolibarr'],
    ['MercaAPI', 'https://mercaapi.automaworks.es/', 'mercaapi',
     'Backend para web y app móvil con consumo de datos en tiempo real y despliegue automático continuo.',
     'FastAPI, Docker, GitHub Actions'],
    ['MercaReact', 'https://mercareact.automaworks.es', 'mercareact',
     'Frontend de comercio electrónico que consume MercaAPI, desplegado en un contenedor independiente.',
     'React, JavaScript'],
    ['Calculadora de préstamos', '/calculadora/', 'calculadora',
     'Simulador de hipotecas y préstamos con Euribor en tiempo real desde el BCE, TAE y cuadro de amortización.',
     'HTML, CSS y JavaScript sin dependencias'],
];

$webs = [
    ['Nómadas Surf', 'https://www.nomadassurf.com/', 'Tienda online de una distribuidora de surf de ámbito nacional, en PrestaShop.'],
    ['Ya Valoraciones', 'https://siniestros.yavaloraciones.com', 'Aplicación web para gestionar y cobrar peritaciones, con tema WordPress propio.'],
    ['Vivero CEEI Cádiz', 'http://vivero.ceeicadiz.com/', 'Gestión de oficinas con planos interactivos de ocupación.'],
    ['The Wave District', 'https://www.thewavedistrict.com/', 'Tienda online de material deportivo con WooCommerce.'],
    ['Adhara Research', 'https://www.adhararesearch.com', 'Web para una agencia de investigación de mercados.'],
    ['Bodente Madrid', 'https://www.bodente.com/', 'Web de restaurante con reservas y carta.'],
];

$lab = [
    ['Recomendador de películas', 'https://peliculas.automaworks.es', 'Búsqueda semántica con procesamiento de lenguaje natural sobre el catálogo de Netflix.'],
    ['Detección de fraude', 'https://dashfinanciero.automaworks.es', 'Análisis de fraude bancario con Apache Spark sobre el conjunto IEEE-CIS.'],
    ['SQL frente a PySpark', 'https://scl.automaworks.es/', 'Análisis bancario que compara consultas SQL con la API de DataFrames de PySpark.'],
];

$page = [
    'title'       => 'Proyectos en producción | AutomaWorks',
    'description' => 'Aplicaciones a medida en producción desarrolladas por AutomaWorks: gestión de comunidades, alquiler de vehículos, localización de flotas, valoración de viviendas y más.',
    'path'        => '/proyectos/',
    'nav'         => 'proyectos',
];
require __DIR__ . '/../includes/header.php';
?>

<header class="page-head">
  <div class="wrap">
    <h1>Proyectos en producción</h1>
    <p>Aplicaciones propias y de clientes que se usan cada día. Cada una resuelve un problema concreto, y todas puedes abrirlas y probarlas.</p>
  </div>
</header>

<section class="section" aria-label="Aplicaciones">
  <div class="wrap">
    <div class="work work--three">
      <?php foreach ($apps as [$name, $url, $img, $text, $tech]): ?>
      <a class="work__item" href="<?= aw_e($url) ?>"<?= str_starts_with($url, 'http') ? ' rel="noopener"' : '' ?>>
        <img class="work__img" src="/assets/proyectos/<?= aw_e($img) ?>.jpg" alt="Captura de <?= aw_e($name) ?>" loading="lazy" width="800" height="500">
        <h2 class="work__title"><?= aw_e($name) ?></h2>
        <p><?= aw_e($text) ?></p>
        <p class="work__tech"><?= aw_e($tech) ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--oat" aria-labelledby="webs">
  <div class="wrap">
    <div class="section__head">
      <h2 id="webs">Webs y comercio electrónico</h2>
      <p>No todo necesita desarrollo a medida. Estas son webs corporativas y tiendas online construidas con WordPress, WooCommerce o PrestaShop.</p>
    </div>
    <ul class="plain-list">
      <?php foreach ($webs as [$name, $url, $text]): ?>
      <li><a href="<?= aw_e($url) ?>" rel="noopener"><?= aw_e($name) ?></a><span><?= aw_e($text) ?></span></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="section" aria-labelledby="lab">
  <div class="wrap">
    <div class="section__head">
      <h2 id="lab">Análisis de datos e IA</h2>
      <p>Proyectos de exploración con grandes volúmenes de datos y procesamiento de lenguaje natural.</p>
    </div>
    <ul class="plain-list">
      <?php foreach ($lab as [$name, $url, $text]): ?>
      <li><a href="<?= aw_e($url) ?>" rel="noopener"><?= aw_e($name) ?></a><span><?= aw_e($text) ?></span></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="section section--flame" aria-labelledby="cta">
  <div class="wrap cta">
    <h2 id="cta">¿Tu proyecto se parece a alguno de estos?</h2>
    <div>
      <p>Cuéntame qué necesitas y te explico cómo lo plantearía.</p>
      <a class="btn btn--ink" href="/contacto/">Cuéntame tu caso</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
