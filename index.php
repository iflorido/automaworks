<?php
$page = [
    'title'       => 'AutomaWorks — Soluciones tecnológicas y transformación digital para empresas',
    'description' => 'Consultoría, software a medida, integraciones, automatización e inteligencia artificial para pymes. AutomaWorks analiza cómo trabaja tu empresa y construye la solución.',
    'path'        => '/',
    'nav'         => 'inicio',
    'assistant'   => true,
    'jsonld'      => json_encode([
        '@context'    => 'https://schema.org',
        '@type'       => 'ProfessionalService',
        'name'        => 'AutomaWorks',
        'url'         => 'https://automaworks.es/',
        'logo'        => 'https://automaworks.es/automaworks.png',
        'email'       => 'contacto@automaworks.es',
        'description' => 'Consultoría tecnológica, software a medida, integraciones, automatización de procesos e inteligencia artificial para empresas.',
        'areaServed'  => 'ES',
        'address'     => ['@type' => 'PostalAddress', 'addressLocality' => 'El Puerto de Santa María', 'addressRegion' => 'Cádiz', 'addressCountry' => 'ES'],
        'founder'     => ['@type' => 'Person', 'name' => 'Ignacio Florido', 'url' => 'https://iflorido.es/'],
        'sameAs'      => ['https://www.linkedin.com/in/ignacio-florido/', 'https://github.com/iflorido'],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
];

$projects = [
    ['AppFincas', 'https://appfincas.com', 'appfincas', 'Gestión de comunidades de propietarios con cuentas claras, votaciones online y comunicación con cada vecino.', ['Django', 'PostgreSQL', 'HTMX']],
    ['AutoRent', 'https://autorent.automaworks.es/', 'autorent', 'Alquiler de vehículos con reservas, contratos, pagos y posición GPS de la flota.', ['Django', 'Celery', 'React']],
    ['NavControl', 'https://navcontrol.automaworks.es/', 'navcontrol', 'Localización de flotas, rutas y avisos en tiempo real, con app móvil para conductores.', ['Django', 'PostGIS', 'Flutter']],
    ['El Precio de tu Casa', 'https://elpreciodetucasa.es/', 'elpreciodetucasa', 'Valoración automática de viviendas con datos públicos de Catastro, Ministerio e INE.', ['FastAPI', 'PostGIS', 'React']],
    ['MapaElectroCarga', 'https://mapaelectrocarga.com', 'mapaelectrocarga', 'Más de 12.000 puntos de recarga para coches eléctricos en España, con conectores y potencia.', ['React', 'FastAPI']],
    ['Dolibarr Tools', 'https://dolibarrtools.automaworks.es/', 'dolibarrtools', 'Módulos para Dolibarr ERP: sincronización con tiendas online, control horario y bolsas de horas.', ['PHP', 'Flask', 'Dolibarr']],
];

require __DIR__ . '/includes/header.php';
?>

<section class="hero" aria-labelledby="hero-title">
  <div class="hero__grid">
    <div class="hero__head">
      <h1 id="hero-title">Transformación digital a la medida de tu empresa.</h1>
    </div>

    <div class="hero__sub">
      <p>Analizo cómo trabajáis, conecto vuestras herramientas, automatizo lo que se repite y desarrollo el software que falta. Hablas directamente con quien diseña y construye la solución.</p>
      <div class="hero__actions">
        <a class="btn btn--ink" href="/contacto/">Cuéntame tu caso</a>
        <a class="btn btn--line" href="/soluciones/">Ver soluciones</a>
      </div>
    </div>

    <a class="tile tile--consult" href="/soluciones/#consultoria">
      <span class="tile__name">Consultoría tecnológica</span>
      <span class="tile__desc">Revisamos tus procesos y decidimos qué digitalizar primero, y qué no.</span>
    </a>
    <a class="tile tile--software" href="/soluciones/#software-a-medida">
      <span class="tile__name">Software a medida</span>
      <span class="tile__desc">Aplicaciones construidas alrededor de tu forma de trabajar.</span>
    </a>
    <a class="tile tile--integr" href="/soluciones/#integraciones">
      <span class="tile__name">Integraciones</span>
      <span class="tile__desc">ERP, CRM y tienda online compartiendo datos sin copiar y pegar.</span>
    </a>
    <a class="tile tile--auto" href="/soluciones/#automatizacion">
      <span class="tile__name">Automatización</span>
      <span class="tile__desc">Informes, avisos y tareas repetitivas que se hacen solos.</span>
    </a>
    <a class="tile tile--ia" href="/soluciones/#inteligencia-artificial">
      <span class="tile__name">Inteligencia artificial</span>
      <span class="tile__desc">Asistentes que responden con la información de tu empresa.</span>
    </a>
  </div>
</section>

<section class="section" aria-labelledby="que-hace">
  <div class="wrap">
    <div class="section__head section__head--split">
      <h2 id="que-hace">Qué hace AutomaWorks</h2>
      <p>Seis formas de resolver un mismo problema: que la tecnología de tu empresa trabaje para ti y no al revés.</p>
    </div>

    <ul class="offer">
      <li>
        <h3 class="offer__name"><span class="offer__swatch" style="background:#2C3B4D"></span><a href="/soluciones/#consultoria">Consultoría tecnológica</a></h3>
        <div class="offer__text">
          <p>Análisis de procesos y herramientas para decidir qué conviene digitalizar, en qué orden y con qué coste.</p>
          <p class="offer__tech">Diagnóstico, hoja de ruta, selección de herramientas</p>
        </div>
      </li>
      <li>
        <h3 class="offer__name"><span class="offer__swatch" style="background:#C9C1B1"></span><a href="/soluciones/#software-a-medida">Software a medida</a></h3>
        <div class="offer__text">
          <p>Aplicaciones de gestión, portales para clientes y plataformas SaaS construidas alrededor de cómo trabajáis.</p>
          <p class="offer__tech">Django, FastAPI, PostgreSQL, React</p>
        </div>
      </li>
      <li>
        <h3 class="offer__name"><span class="offer__swatch" style="background:#FFB162"></span><a href="/soluciones/#integraciones">Integraciones</a></h3>
        <div class="offer__text">
          <p>ERP, CRM, tienda online, pasarelas de pago y servicios externos intercambiando datos sin intervención manual.</p>
          <p class="offer__tech">APIs REST, webhooks, colas de tareas</p>
        </div>
      </li>
      <li>
        <h3 class="offer__name"><span class="offer__swatch" style="background:#A35139"></span><a href="/soluciones/#automatizacion">Automatización</a></h3>
        <div class="offer__text">
          <p>Informes, avisos, importaciones y procesos que hoy dependen de que alguien se acuerde de hacerlos.</p>
          <p class="offer__tech">Celery, tareas programadas, scripts en Python</p>
        </div>
      </li>
      <li>
        <h3 class="offer__name"><span class="offer__swatch" style="background:#1B2632"></span><a href="/soluciones/#inteligencia-artificial">Inteligencia artificial</a></h3>
        <div class="offer__text">
          <p>Asistentes que responden con vuestra documentación, clasificación de correos y extracción de datos de documentos.</p>
          <p class="offer__tech">RAG, embeddings, modelos de OpenAI y Anthropic</p>
        </div>
      </li>
      <li>
        <h3 class="offer__name"><span class="offer__swatch" style="background:transparent;outline:2px solid #1B2632;outline-offset:-2px"></span><a href="/soluciones/#dolibarr">Dolibarr ERP</a></h3>
        <div class="offer__text">
          <p>Implantación, módulos propios y sincronización con WooCommerce y PrestaShop, incluidos módulos con licencia comercial.</p>
          <p class="offer__tech">PHP, módulos Dolibarr, API REST</p>
        </div>
      </li>
    </ul>

    <p class="section__foot"><a class="link" href="/soluciones/">Ver cada solución en detalle</a></p>
  </div>
</section>

<section class="section section--oat" aria-labelledby="proyectos">
  <div class="wrap">
    <div class="section__head section__head--split">
      <h2 id="proyectos">Proyectos en producción</h2>
      <p>Herramientas que se usan cada día. Puedes abrirlas y probarlas.</p>
    </div>

    <div class="work">
      <?php foreach ($projects as [$name, $url, $img, $text, $tech]): ?>
      <a class="work__item" href="<?= aw_e($url) ?>" rel="noopener">
        <div class="work__frame">
          <img class="work__img" src="/assets/proyectos/<?= aw_e($img) ?>.jpg" alt="Captura de <?= aw_e($name) ?>" loading="lazy" width="800" height="500">
        </div>
        <h3 class="work__title"><?= aw_e($name) ?></h3>
        <p><?= aw_e($text) ?></p>
        <ul class="chips" aria-label="Tecnología"><?php foreach ($tech as $t): ?><li><?= aw_e($t) ?></li><?php endforeach; ?></ul>
      </a>
      <?php endforeach; ?>
    </div>

    <p class="section__foot"><a class="link" href="/proyectos/">Ver todos los proyectos</a></p>
  </div>
</section>

<section class="section section--dark on-dark" aria-labelledby="metodo">
  <div class="wrap">
    <div class="section__head section__head--split">
      <h2 id="metodo">Cómo trabajo</h2>
      <p>Cuatro pasos, siempre en el mismo orden. Cada uno se apoya en el anterior, y no se avanza al siguiente sin que lo hayas validado.</p>
    </div>
    <ol class="steps">
      <li>
        <div>
          <h3>Entender</h3>
          <p>Veo cómo trabajáis hoy: quién hace qué, con qué herramientas y dónde se atasca.</p>
        </div>
      </li>
      <li>
        <div>
          <h3>Proponer</h3>
          <p>Una solución concreta, con alcance, plazos y coste claros. A veces la respuesta es que no hace falta desarrollar nada.</p>
        </div>
      </li>
      <li>
        <div>
          <h3>Construir</h3>
          <p>Por fases cortas que puedes probar. Ves la herramienta funcionando desde las primeras semanas.</p>
        </div>
      </li>
      <li>
        <div>
          <h3>Mantener</h3>
          <p>Despliegue, copias de seguridad, monitorización y mejoras para que siga funcionando cuando cambie tu negocio.</p>
        </div>
      </li>
    </ol>
  </div>
</section>

<section class="section" id="quien" aria-labelledby="quien-titulo">
  <div class="wrap person">
    <div class="person__brand" aria-hidden="true">
      <svg class="person__mark" viewBox="0 0 20 20">
        <rect x="0"  y="0"  width="12" height="9"  fill="#2C3B4D"/>
        <rect x="12" y="0"  width="8"  height="13" fill="#A35139"/>
        <rect x="0"  y="9"  width="12" height="11" fill="#FFB162"/>
        <rect x="12" y="13" width="8"  height="7"  fill="#1B2632"/>
      </svg>
      <span class="person__wordmark">AutomaWorks</span>
    </div>

    <div class="person__body">
      <h2 id="quien-titulo">Quién hay detrás</h2>
      <p>AutomaWorks es el estudio de desarrollo de Ignacio Florido, con más de veinte años en proyectos web y los últimos cinco especializado en backend con Python. Empezó con PHP y MySQL, pasó por la administración de servidores y hoy diseña, construye y despliega aplicaciones completas.</p>
      <p>Trabajar con AutomaWorks significa tratar siempre con la misma persona, de la primera conversación al mantenimiento. Sin intermediarios ni traspasos entre departamentos.</p>
      <dl class="facts">
        <dt>Dónde</dt>
        <dd>El Puerto de Santa María (Cádiz). En remoto para toda España.</dd>
        <dt>Formación</dt>
        <dd>Máster avanzado de programación en Python para hacking, big data y machine learning.</dd>
      </dl>
      <div class="signature">
        <img src="/assets/img/ignacio-florido.jpg" alt="" width="136" height="136" loading="lazy">
        <div>
          <strong>Ignacio Florido</strong>
          <span>Fundador y desarrollador. Perfil completo en <a class="link" href="https://iflorido.es">iflorido.es</a></span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--blue on-dark" id="asistente" aria-labelledby="asistente-titulo">
  <div class="wrap assistant">
    <div class="assistant__intro">
      <h2 id="asistente-titulo">Pregunta antes de escribir</h2>
      <p>Este asistente conoce las soluciones, los proyectos y la forma de trabajar de AutomaWorks. Úsalo para resolver dudas rápidas; si encaja, el siguiente paso es una conversación.</p>
      <small>Las respuestas las genera una IA a partir de información de esta web y pueden contener imprecisiones.</small>
    </div>
    <div id="awa-root"></div>
  </div>
</section>

<section class="section section--flame" aria-labelledby="cta">
  <div class="wrap cta">
    <h2 id="cta">¿Hay un proceso en tu empresa que te quita horas cada semana?</h2>
    <div>
      <p>Cuéntamelo en unas líneas. Te respondo personalmente con una primera valoración.</p>
      <a class="btn btn--ink" href="/contacto/">Cuéntame tu caso</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
