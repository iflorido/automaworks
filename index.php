<?php
$page = [
    'title'       => 'AutomaWorks — Software a medida, integraciones e IA para empresas',
    'description' => 'Desarrollo de software a medida, integraciones entre ERP, CRM y tienda online, automatización de procesos y asistentes de IA para pymes. Trato directo con quien lo construye.',
    'path'        => '/',
    'nav'         => '',
    'assistant'   => true,
    'jsonld'      => json_encode([
        '@context'    => 'https://schema.org',
        '@type'       => 'ProfessionalService',
        'name'        => 'AutomaWorks',
        'url'         => 'https://automaworks.es/',
        'logo'        => 'https://automaworks.es/automaworks.png',
        'email'       => 'contacto@automaworks.es',
        'description' => 'Software a medida, integraciones, automatización de procesos e inteligencia artificial para empresas.',
        'areaServed'  => 'ES',
        'address'     => ['@type' => 'PostalAddress', 'addressLocality' => 'El Puerto de Santa María', 'addressRegion' => 'Cádiz', 'addressCountry' => 'ES'],
        'founder'     => ['@type' => 'Person', 'name' => 'Ignacio Florido', 'url' => 'https://iflorido.es/'],
        'sameAs'      => ['https://www.linkedin.com/in/ignacio-florido/', 'https://github.com/iflorido'],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
];
require __DIR__ . '/includes/header.php';
?>

<section class="hero" aria-labelledby="hero-title">
  <div class="hero__grid">
    <div class="hero__head">
      <h1 id="hero-title">Software que encaja en cómo trabaja tu empresa.</h1>
    </div>

    <div class="hero__sub">
      <p>AutomaWorks desarrolla aplicaciones, integraciones y asistentes de IA para empresas que han crecido más rápido que sus herramientas. Hablas directamente con quien diseña y construye la solución.</p>
      <div class="hero__actions">
        <a class="btn btn--ink" href="/contacto/">Cuéntame tu caso</a>
        <a class="btn btn--line" href="/soluciones/">Ver soluciones</a>
      </div>
    </div>

    <div class="tile tile--sys tile--erp" aria-hidden="true">
      <span class="tile__role">Stock, compras y facturación</span>
      <span class="tile__name">ERP</span>
    </div>
    <div class="tile tile--sys tile--sheets" aria-hidden="true">
      <span class="tile__role">Lo que alguien apunta a mano</span>
      <span class="tile__name">Hojas de cálculo</span>
    </div>
    <div class="tile tile--sys tile--shop" aria-hidden="true">
      <span class="tile__role">Pedidos que entran a cualquier hora</span>
      <span class="tile__name">Tienda online</span>
    </div>
    <div class="tile tile--sys tile--invoices" aria-hidden="true">
      <span class="tile__role">Lo que hay que emitir y cobrar</span>
      <span class="tile__name">Facturación</span>
    </div>
    <div class="tile tile--sys tile--clients" aria-hidden="true">
      <span class="tile__role">Quien te escribe cada día</span>
      <span class="tile__name">Clientes y correo</span>
    </div>
  </div>
</section>

<section class="section" aria-labelledby="situaciones">
  <div class="wrap">
    <div class="section__head">
      <h2 id="situaciones">Casi siempre empieza por una de estas situaciones</h2>
      <p>No hace falta saber qué tecnología necesitas. Basta con reconocer dónde se va el tiempo.</p>
    </div>

    <ul class="symptoms">
      <li>
        <p class="symptoms__quote">Los mismos datos se escriben en tres programas distintos.</p>
        <p class="symptoms__answer">Conecto tus herramientas para que pedidos, clientes y facturas pasen de una a otra sin copiar y pegar.</p>
        <a class="link" href="/soluciones/#integraciones">Integraciones</a>
      </li>
      <li>
        <p class="symptoms__quote">El equipo se ha adaptado al programa, y no al revés.</p>
        <p class="symptoms__answer">Desarrollo la herramienta que encaja con vuestro proceso real, no con el de un catálogo genérico.</p>
        <a class="link" href="/soluciones/#software-a-medida">Software a medida</a>
      </li>
      <li>
        <p class="symptoms__quote">Cada lunes alguien monta el mismo informe a mano.</p>
        <p class="symptoms__answer">Automatizo informes, avisos y sincronizaciones para que ocurran solos y a tiempo.</p>
        <a class="link" href="/soluciones/#automatizacion">Automatización</a>
      </li>
      <li>
        <p class="symptoms__quote">La respuesta está en algún documento, pero nadie la encuentra.</p>
        <p class="symptoms__answer">Asistentes de IA que consultan vuestra documentación y responden citando de dónde sale cada dato.</p>
        <a class="link" href="/soluciones/#inteligencia-artificial">Inteligencia artificial</a>
      </li>
    </ul>
  </div>
</section>

<section class="section section--dark on-dark" aria-labelledby="que-hace">
  <div class="wrap">
    <div class="section__head">
      <h2 id="que-hace">Qué hace AutomaWorks</h2>
      <p>Cinco formas de resolver un mismo problema: que la tecnología de tu empresa trabaje para ti y no al revés.</p>
    </div>

    <ul class="offer">
      <li>
        <h3 class="offer__name"><span class="offer__swatch" style="background:#FFB162"></span><a href="/soluciones/#software-a-medida">Software a medida</a></h3>
        <div class="offer__text">
          <p>Aplicaciones de gestión, portales para clientes y plataformas SaaS construidas alrededor de cómo trabajáis.</p>
          <p class="offer__tech">Django, FastAPI, PostgreSQL, React</p>
        </div>
      </li>
      <li>
        <h3 class="offer__name"><span class="offer__swatch" style="background:#C9C1B1"></span><a href="/soluciones/#integraciones">Integraciones</a></h3>
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
        <h3 class="offer__name"><span class="offer__swatch" style="background:#EEE9DF"></span><a href="/soluciones/#inteligencia-artificial">Inteligencia artificial</a></h3>
        <div class="offer__text">
          <p>Asistentes que responden con vuestra documentación, clasificación de correos y extracción de datos de documentos.</p>
          <p class="offer__tech">RAG, embeddings, modelos de OpenAI y Anthropic</p>
        </div>
      </li>
      <li>
        <h3 class="offer__name"><span class="offer__swatch" style="background:#2C3B4D;outline:1px solid #C9C1B1"></span><a href="/soluciones/#dolibarr">Dolibarr ERP</a></h3>
        <div class="offer__text">
          <p>Implantación, módulos propios y sincronización con WooCommerce y PrestaShop, incluidos módulos con licencia comercial.</p>
          <p class="offer__tech">PHP, módulos Dolibarr, API REST</p>
        </div>
      </li>
    </ul>

    <p class="section__foot"><a class="link" href="/soluciones/">Ver cada solución en detalle</a></p>
  </div>
</section>

<section class="section" aria-labelledby="proyectos">
  <div class="wrap">
    <div class="section__head">
      <h2 id="proyectos">Proyectos en producción</h2>
      <p>Herramientas que se usan cada día. Puedes abrirlas y probarlas.</p>
    </div>

    <div class="work">
      <a class="work__item" href="https://appfincas.com" rel="noopener">
        <img class="work__img" src="/assets/proyectos/appfincas.jpg" alt="Panel de AppFincas con las cuentas de una comunidad de propietarios" loading="lazy" width="800" height="500">
        <h3>AppFincas</h3>
        <p>Gestión de comunidades de propietarios: cuentas claras, votaciones online y comunicación directa con cada vecino.</p>
        <p class="work__tech">Django, PostgreSQL, HTMX</p>
      </a>
      <a class="work__item" href="https://autorent.automaworks.es/" rel="noopener">
        <img class="work__img" src="/assets/proyectos/autorent.jpg" alt="Plataforma AutoRent con el calendario de reservas de vehículos" loading="lazy" width="800" height="500">
        <h3>AutoRent</h3>
        <p>Alquiler de vehículos con reservas, contratos, pagos y posición GPS de la flota.</p>
        <p class="work__tech">Django, Celery, PostgreSQL, React</p>
      </a>
      <a class="work__item" href="https://navcontrol.automaworks.es/" rel="noopener">
        <img class="work__img" src="/assets/proyectos/navcontrol.jpg" alt="Mapa de NavControl con la ubicación de una flota en tiempo real" loading="lazy" width="800" height="500">
        <h3>NavControl</h3>
        <p>Localización de flotas, rutas y avisos en tiempo real, con aplicación móvil para conductores.</p>
        <p class="work__tech">Django, PostGIS, Flutter</p>
      </a>
      <a class="work__item" href="https://elpreciodetucasa.es/" rel="noopener">
        <img class="work__img" src="/assets/proyectos/elpreciodetucasa.jpg" alt="Valoración de una vivienda en El Precio de tu Casa" loading="lazy" width="800" height="500">
        <h3>El Precio de tu Casa</h3>
        <p>Valoración automática de viviendas con datos públicos de Catastro, Ministerio e INE, explicada paso a paso.</p>
        <p class="work__tech">FastAPI, PostgreSQL con PostGIS, React</p>
      </a>
    </div>

    <p class="section__foot"><a class="link" href="/proyectos/">Ver todos los proyectos</a></p>
  </div>
</section>

<section class="section section--oat" aria-labelledby="metodo">
  <div class="wrap">
    <div class="section__head">
      <h2 id="metodo">Cómo trabajo</h2>
    </div>
    <ol class="steps">
      <li>
        <h3>Entender</h3>
        <p>Antes de proponer nada, veo cómo trabajáis hoy: quién hace qué, con qué herramientas y dónde se atasca.</p>
      </li>
      <li>
        <h3>Proponer</h3>
        <p>Una solución concreta, con alcance, plazos y coste claros antes de empezar. A veces la respuesta es que no hace falta desarrollar nada.</p>
      </li>
      <li>
        <h3>Construir</h3>
        <p>Por fases cortas que puedes probar. Ves la herramienta funcionando desde las primeras semanas.</p>
      </li>
      <li>
        <h3>Mantener</h3>
        <p>Despliegue, copias de seguridad, monitorización y mejoras para que siga funcionando cuando cambie tu negocio.</p>
      </li>
    </ol>
  </div>
</section>

<section class="section" id="quien" aria-labelledby="quien-titulo">
  <div class="wrap person">
    <img class="person__photo" src="/assets/img/ignacio-florido.jpg" alt="Ignacio Florido" loading="lazy" width="640" height="800">
    <div class="person__body">
      <h2 id="quien-titulo">Quién hay detrás</h2>
      <p>AutomaWorks es el estudio de desarrollo de Ignacio Florido, con más de veinte años en proyectos web y los últimos cinco especializado en backend con Python. Empezó con PHP y MySQL, pasó por la administración de servidores y hoy diseña, construye y despliega aplicaciones completas.</p>
      <p>Trabajar con AutomaWorks significa tratar siempre con la misma persona, de la primera conversación al mantenimiento. Sin intermediarios ni traspasos entre departamentos.</p>
      <dl class="facts">
        <dt>Dónde</dt>
        <dd>El Puerto de Santa María (Cádiz). En remoto para toda España.</dd>
        <dt>Formación</dt>
        <dd>Máster avanzado de programación en Python para hacking, big data y machine learning.</dd>
        <dt>Perfil completo</dt>
        <dd><a class="link" href="https://iflorido.es">iflorido.es</a></dd>
      </dl>
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
