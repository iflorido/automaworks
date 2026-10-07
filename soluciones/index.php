<?php
$faq = [
    ['¿Cuánto cuesta un desarrollo a medida?',
     'Depende del alcance, del número de usuarios y de las integraciones necesarias. Antes de dar una cifra analizo el caso; después recibes una propuesta con alcance, plazos y coste cerrados. Si algo puede resolverse con una herramienta que ya existe, te lo diré antes de presupuestar.'],
    ['¿Cuánto se tarda?',
     'Una integración concreta puede estar en semanas; una aplicación completa, en meses. Divido el trabajo en fases para que la parte más útil funcione cuanto antes y el resto se construya sobre algo que ya estás usando.'],
    ['¿Podéis continuar un software que ya existe?',
     'Sí, siempre que el código y la arquitectura lo permitan. Primero reviso el estado del proyecto y te digo con claridad si conviene continuarlo, mejorarlo por partes o replantearlo.'],
    ['¿Quién hace el trabajo?',
     'Ignacio Florido, personalmente: análisis, desarrollo, despliegue y mantenimiento. Eso determina el tamaño de proyecto que acepto, porque prefiero comprometer plazos que pueda cumplir.'],
    ['¿Dónde se alojan las aplicaciones?',
     'En servidores en Europa, desplegadas con Docker y con copias de seguridad. Si tu empresa ya tiene infraestructura propia, se puede desplegar allí.'],
    ['¿Hay mantenimiento después de la entrega?',
     'Sí. Incidencias, actualizaciones de seguridad, copias, monitorización y nuevas funciones a medida que cambie tu negocio.'],
];

$faqLd = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(fn($q) => [
        '@type' => 'Question', 'name' => $q[0],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $q[1]],
    ], $faq),
];

$page = [
    'title'       => 'Soluciones: software a medida, integraciones, automatización e IA | AutomaWorks',
    'description' => 'Software a medida, integraciones entre ERP, CRM y tienda online, automatización de procesos, inteligencia artificial con tus datos y Dolibarr ERP. Cómo lo resuelve AutomaWorks.',
    'path'        => '/soluciones/',
    'nav'         => 'soluciones',
    'jsonld'      => json_encode($faqLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
];
require __DIR__ . '/../includes/header.php';
?>

<header class="page-head">
  <div class="wrap">
    <h1>Soluciones para que tu empresa funcione con menos fricción</h1>
    <p>Cada bloque empieza por una situación que probablemente reconozcas. Lo que viene después es cómo la resuelvo y con qué tecnología.</p>
    <ul class="page-index" aria-label="En esta página">
      <li><a href="#consultoria">Consultoría</a></li>
      <li><a href="#software-a-medida">Software a medida</a></li>
      <li><a href="#integraciones">Integraciones</a></li>
      <li><a href="#automatizacion">Automatización</a></li>
      <li><a href="#inteligencia-artificial">Inteligencia artificial</a></li>
      <li><a href="#dolibarr">Dolibarr ERP</a></li>
      <li><a href="#preguntas">Preguntas frecuentes</a></li>
    </ul>
  </div>
</header>

<div class="wrap">

  <section class="solution" id="consultoria" aria-labelledby="s0">
    <div class="solution__aside">
      <span class="solution__swatch" style="background:#2C3B4D"></span>
      <h2 id="s0">Consultoría tecnológica</h2>
    </div>
    <div class="solution__body">
      <p class="solution__lead">Sabes que hay que digitalizar algo, pero no por dónde empezar.</p>
      <p>La transformación digital no empieza comprando un programa, sino entendiendo cómo funciona la empresa. Reviso vuestros procesos y herramientas, detecto dónde se pierde tiempo o dinero y te propongo un plan por fases con prioridades claras.</p>
      <h3>Qué recibes</h3>
      <ul class="ticks">
        <li>Un diagnóstico de cómo circula hoy la información entre personas y herramientas.</li>
        <li>Una hoja de ruta ordenada por impacto y coste: qué hacer primero y qué puede esperar.</li>
        <li>Recomendaciones de herramientas existentes cuando no compensa desarrollar.</li>
        <li>Una estimación realista de cada fase antes de comprometer presupuesto.</li>
      </ul>
      <p class="tech-line">Análisis de procesos, arquitectura de sistemas, selección de herramientas</p>
    </div>
  </section>

  <section class="solution" id="software-a-medida" aria-labelledby="s1">
    <div class="solution__aside">
      <span class="solution__swatch" style="background:#C9C1B1"></span>
      <h2 id="s1">Software a medida</h2>
    </div>
    <div class="solution__body">
      <p class="solution__lead">El equipo se ha adaptado al programa, y no al revés.</p>
      <p>Las herramientas estándar funcionan mientras el proceso también lo es. Cuando tu empresa tiene una forma propia de trabajar, acaba rodeada de excepciones, hojas de cálculo auxiliares y pasos que solo conoce una persona. Ahí una aplicación propia deja de ser un lujo.</p>
      <h3>Qué puedo construir</h3>
      <ul class="ticks">
        <li>Aplicaciones de gestión internas: clientes, proyectos, presupuestos, pedidos, inventario o personal.</li>
        <li>Portales y áreas privadas para clientes, proveedores o empleados.</li>
        <li>Plataformas SaaS con usuarios, permisos y planes de suscripción.</li>
        <li>Sistemas con lógica propia: reservas, control de flotas, gestión documental o planificación.</li>
      </ul>
      <p class="tech-line">Python, Django, FastAPI, PostgreSQL, React, HTMX, Docker</p>
    </div>
  </section>

  <section class="solution" id="integraciones" aria-labelledby="s2">
    <div class="solution__aside">
      <span class="solution__swatch" style="background:#FFB162"></span>
      <h2 id="s2">Integraciones</h2>
    </div>
    <div class="solution__body">
      <p class="solution__lead">Los mismos datos se escriben en tres programas distintos.</p>
      <p>Cada copia manual es tiempo perdido y una oportunidad de error. Conecto los sistemas que ya usas para que la información fluya sola: un pedido entra en la tienda y aparece en el ERP, el stock se actualiza y la factura sale sin que nadie la teclee.</p>
      <h3>Qué puedo conectar</h3>
      <ul class="ticks">
        <li>ERP y CRM con tiendas online como WooCommerce o PrestaShop.</li>
        <li>Pasarelas de pago, facturación electrónica y plataformas bancarias.</li>
        <li>APIs de terceros, servicios en la nube y bases de datos existentes.</li>
        <li>Una API propia para que otras aplicaciones, o tu app móvil, consuman tus datos.</li>
      </ul>
      <p class="tech-line">APIs REST, webhooks, Celery, autenticación JWT, HMAC</p>
    </div>
  </section>

  <section class="solution" id="automatizacion" aria-labelledby="s3">
    <div class="solution__aside">
      <span class="solution__swatch" style="background:#A35139"></span>
      <h2 id="s3">Automatización</h2>
    </div>
    <div class="solution__body">
      <p class="solution__lead">Cada lunes alguien monta el mismo informe a mano.</p>
      <p>Muchas tareas no necesitan una aplicación nueva, sino que ocurran solas. Identifico las rutinas que se repiten y las convierto en procesos automáticos que avisan cuando algo no cuadra.</p>
      <h3>Qué suele automatizarse</h3>
      <ul class="ticks">
        <li>Informes y cuadros de mando que se generan y envían en la fecha prevista.</li>
        <li>Importación y limpieza de datos desde ficheros, correos o portales.</li>
        <li>Avisos por correo cuando un pedido, un pago o un servidor necesitan atención.</li>
        <li>Sincronizaciones periódicas entre sistemas que no tienen integración directa.</li>
      </ul>
      <p class="tech-line">Python, Celery, tareas programadas, Pandas, PySpark para grandes volúmenes</p>
    </div>
  </section>

  <section class="solution" id="inteligencia-artificial" aria-labelledby="s4">
    <div class="solution__aside">
      <span class="solution__swatch" style="background:#1B2632"></span>
      <h2 id="s4">Inteligencia artificial</h2>
    </div>
    <div class="solution__body">
      <p class="solution__lead">La respuesta está en algún documento, pero nadie la encuentra.</p>
      <p>La IA aporta valor cuando trabaja con tu información y dentro de tus procesos, no como un chat genérico. Construyo asistentes que consultan vuestra documentación, procesan documentos y se conectan con las herramientas que ya usáis.</p>
      <h3>Qué puedo integrar</h3>
      <ul class="ticks">
        <li>Asistentes que responden a clientes o al equipo con vuestra propia documentación (RAG).</li>
        <li>Extracción de datos de facturas, albaranes, contratos o formularios.</li>
        <li>Clasificación y respuesta asistida de correos y solicitudes.</li>
        <li>Funciones de IA dentro de aplicaciones que ya existen.</li>
      </ul>
      <p>El asistente de esta web es un ejemplo: un sistema RAG propio que responde con la información de AutomaWorks.</p>
      <p class="tech-line">OpenAI, Anthropic, embeddings, ChromaDB, FastAPI</p>
    </div>
  </section>

  <section class="solution" id="dolibarr" aria-labelledby="s5">
    <div class="solution__aside">
      <span class="solution__swatch" style="outline:2px solid #1B2632;outline-offset:-2px"></span>
      <h2 id="s5">Dolibarr ERP</h2>
    </div>
    <div class="solution__body">
      <p class="solution__lead">Necesitas un ERP, pero no uno que te obligue a cambiar cómo trabajas.</p>
      <p>Dolibarr es un ERP y CRM de código abierto, sin licencias por usuario, que se adapta bien a pymes. Lo implanto, desarrollo módulos a medida y lo conecto con tu tienda online.</p>
      <h3>Qué incluye</h3>
      <ul class="ticks">
        <li>Implantación, configuración y migración de datos.</li>
        <li>Sincronización de catálogo, stock y pedidos con WooCommerce y PrestaShop.</li>
        <li>Módulos propios con licencia comercial: control horario, bolsas de horas de mantenimiento y más.</li>
        <li>Desarrollo de módulos específicos para tu proceso.</li>
      </ul>
      <p><a class="link" href="https://dolibarrtools.automaworks.es/">Dolibarr Tools</a></p>
      <p class="tech-line">PHP, API REST de Dolibarr, Python para scripts de sincronización</p>
    </div>
  </section>

</div>

<section class="section section--dark on-dark" aria-labelledby="honesto">
  <div class="wrap honest">
    <h2 id="honesto">Cuándo no necesitas software a medida</h2>
    <div>
      <p>Si una herramienta que ya existe resuelve bien el problema, casi siempre será más barato usarla. Una web corporativa o una tienda pequeña funcionan perfectamente con WordPress o WooCommerce.</p>
      <p>El desarrollo a medida tiene sentido cuando el proceso es propio, cuando hay que conectar varios sistemas o cuando el coste de las tareas manuales supera al de automatizarlas. Si tu caso no cumple nada de esto, te lo diré en la primera conversación.</p>
    </div>
  </div>
</section>

<section class="section" aria-labelledby="tecnologia">
  <div class="wrap">
    <div class="section__head">
      <h2 id="tecnologia">Tecnología</h2>
      <p>Herramientas maduras y de código abierto, elegidas según el proyecto. Sin dependencia de un proveedor que te ate.</p>
    </div>
    <dl class="stack">
      <div><dt>Backend</dt><dd>Python, Django, Django REST Framework, FastAPI, Flask</dd></div>
      <div><dt>Frontend</dt><dd>React, HTMX, JavaScript, Flutter para móvil</dd></div>
      <div><dt>Datos</dt><dd>PostgreSQL, PostGIS, Redis, MySQL, Pandas, PySpark</dd></div>
      <div><dt>Procesos</dt><dd>Celery, tareas programadas, webhooks</dd></div>
      <div><dt>Inteligencia artificial</dt><dd>OpenAI, Anthropic, embeddings, ChromaDB</dd></div>
      <div><dt>Infraestructura</dt><dd>Docker, Nginx, Linux, GitHub Actions, Plesk</dd></div>
    </dl>
  </div>
</section>

<section class="section section--oat" id="preguntas" aria-labelledby="faq-titulo">
  <div class="wrap">
    <div class="section__head">
      <h2 id="faq-titulo">Preguntas frecuentes</h2>
    </div>
    <div class="faq">
      <?php foreach ($faq as $q): ?>
      <details>
        <summary><?= aw_e($q[0]) ?></summary>
        <p><?= aw_e($q[1]) ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--flame" aria-labelledby="cta">
  <div class="wrap cta">
    <h2 id="cta">¿Tienes un proceso que mejorar o un problema que resolver?</h2>
    <div>
      <p>Cuéntame cómo trabajáis hoy y qué necesitas conseguir. Te diré si tiene sentido desarrollar algo, integrar lo que ya tienes o ninguna de las dos cosas.</p>
      <a class="btn btn--ink" href="/contacto/">Cuéntame tu caso</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
