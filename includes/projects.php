<?php
/**
 * Proyectos: fuente única para Inicio (destacados) y /proyectos/.
 * Imágenes en /assets/proyectos/ (mismas que iflorido.es).
 */

const AW_PROJECT_GROUPS = [
    'gestion'     => 'Gestión y operación',
    'datos'       => 'Datos públicos y mapas',
    'integracion' => 'Integraciones y ERP',
    'herramientas'=> 'Herramientas web',
];

const AW_PROJECTS = [
    [
        'slug'     => 'appfincas',
        'name'     => 'AppFincas',
        'url'      => 'https://appfincas.com',
        'group'    => 'gestion',
        'featured' => true,
        'context'  => 'La gestión de una comunidad de propietarios suele repartirse entre hojas de cálculo, correos y juntas presenciales difíciles de convocar.',
        'solution' => 'Un portal único con las cuentas claras para cada propietario, juntas con voto online, remesas SEPA, comunicación directa con el administrador y app móvil.',
        'stack'    => ['Django', 'PostgreSQL', 'HTMX', 'Flutter', 'Docker'],
    ],
    [
        'slug'     => 'autorent',
        'name'     => 'AutoRent',
        'url'      => 'https://autorent.automaworks.es/',
        'group'    => 'gestion',
        'featured' => true,
        'context'  => 'Una empresa de alquiler necesita controlar flota, reservas, contratos y cobros sin saltar de una herramienta a otra.',
        'solution' => 'Plataforma de alquiler con reservas, contratos, disponibilidad y pagos, posición GPS de los vehículos y tareas automáticas en segundo plano.',
        'stack'    => ['Django', 'PostgreSQL', 'React', 'Celery', 'Docker'],
    ],
    [
        'slug'     => 'elpreciodetucasa',
        'name'     => 'El Precio de tu Casa',
        'url'      => 'https://elpreciodetucasa.es/',
        'group'    => 'datos',
        'featured' => true,
        'context'  => 'Las valoraciones online dependen de anuncios de portales y rara vez explican de dónde sale la cifra.',
        'solution' => 'Valorador automático de viviendas construido solo con datos públicos (Catastro, Ministerio de Vivienda e INE) que explica la estimación paso a paso.',
        'stack'    => ['FastAPI', 'PostgreSQL', 'PostGIS', 'React'],
    ],
    [
        'slug'     => 'navcontrol',
        'name'     => 'NavControl',
        'url'      => 'https://navcontrol.automaworks.es/',
        'group'    => 'gestion',
        'featured' => true,
        'context'  => 'Saber dónde está cada vehículo, qué ruta ha hecho y cuándo algo se sale de lo previsto.',
        'solution' => 'Localización de flotas en tiempo real con histórico de rutas, avisos y app móvil, sobre una base de datos geoespacial.',
        'stack'    => ['Django', 'PostGIS', 'Flutter'],
    ],
    [
        'slug'     => 'ofigest',
        'name'     => 'OfiGest',
        'url'      => 'https://ofigest.automaworks.es/',
        'group'    => 'gestion',
        'featured' => false,
        'context'  => 'Oficinas, plazas de aparcamiento y trasteros con contratos y ocupación que cambian cada mes.',
        'solution' => 'Gestión del alquiler de espacios: inventario, contratos, ocupación y módulos para cada tipo de espacio.',
        'stack'    => ['Django', 'PostgreSQL', 'JavaScript'],
    ],
    [
        'slug'     => 'mapaelectrocarga',
        'name'     => 'MapaElectroCarga',
        'url'      => 'https://mapaelectrocarga.com',
        'group'    => 'datos',
        'featured' => false,
        'context'  => 'Los datos abiertos de recarga existen, pero no en un formato que un conductor pueda consultar.',
        'solution' => 'Mapa de más de 12.000 puntos de recarga en España con conectores, potencia y operador, a partir de datos abiertos de la DGT.',
        'stack'    => ['React', 'FastAPI', 'SQL'],
    ],
    [
        'slug'     => 'mapagasolina',
        'name'     => 'MapaGasolina',
        'url'      => 'https://mapagasolina.com/',
        'group'    => 'datos',
        'featured' => false,
        'context'  => 'Los precios de combustible se publican a diario, pero compararlos por zona no es inmediato.',
        'solution' => 'Precios de combustible en España con datos del Ministerio, filtrables por zona y tipo de carburante.',
        'stack'    => ['FastAPI', 'SQL', 'JavaScript'],
    ],
    [
        'slug'     => 'dolibarrtools',
        'name'     => 'Dolibarr Tools',
        'url'      => 'https://dolibarrtools.automaworks.es/',
        'group'    => 'integracion',
        'featured' => false,
        'context'  => 'Dolibarr cubre mucho, pero no habla de serie con todas las tiendas online ni con todos los procesos de una empresa.',
        'solution' => 'Módulos para Dolibarr ERP: sincronización con WooCommerce y PrestaShop, control horario y bolsas de horas, con validación de licencia.',
        'stack'    => ['PHP', 'Python', 'Flask', 'Dolibarr'],
    ],
    [
        'slug'     => 'mercareact',
        'name'     => 'MercaAPI y MercaReact',
        'url'      => 'https://mercareact.automaworks.es',
        'group'    => 'integracion',
        'featured' => false,
        'context'  => 'Una API pensada para servir a la vez a una web y a una app móvil, y desplegarse sin intervención manual.',
        'solution' => 'API en FastAPI con frontend en React para una tienda simulada, con despliegue automático mediante GitHub Actions, Docker y Watchtower.',
        'stack'    => ['FastAPI', 'React', 'Docker', 'GitHub Actions'],
        'image'    => 'mercareact',
    ],
    [
        'slug'     => 'calculadora',
        'name'     => 'Calculadora de préstamos',
        'url'      => 'https://automaworks.es/calculadora/',
        'group'    => 'herramientas',
        'featured' => false,
        'context'  => 'Comparar hipotecas exige algo más que la cuota: TAE real, Euríbor y cuánto se paga en intereses.',
        'solution' => 'Simulador de hipotecas y préstamos con TAE real, Euríbor actualizado desde el BCE y tabla de amortización, sin servidor detrás.',
        'stack'    => ['HTML', 'CSS', 'JavaScript'],
    ],
];

/** Otros trabajos más pequeños o de análisis de datos (lista breve). */
const AW_LAB = [
    ['name' => 'CineSearch', 'url' => 'https://peliculas.automaworks.es/', 'text' => 'Recomendador de películas por similitud semántica (NLP).'],
    ['name' => 'Detección de fraude', 'url' => 'https://dashfinanciero.automaworks.es/', 'text' => 'Análisis de fraude bancario con Apache Spark sobre el dataset IEEE-CIS.'],
    ['name' => 'PySpark y SQL', 'url' => 'https://scl.automaworks.es/', 'text' => 'Análisis bancario que compara consultas SQL con la API de DataFrames de PySpark.'],
];

function aw_project_image(array $p): string
{
    return '/assets/proyectos/' . ($p['image'] ?? $p['slug']) . '.jpg';
}

function aw_stack(array $p): string
{
    return implode(', ', $p['stack']);
}
