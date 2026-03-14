<?php
require_once 'conex.php';

// ── Helpers ────────────────────────────────────────────────
function highlightSQL(string $sql): string {
    $sql = str_replace(['<', '>'], ['&lt;', '&gt;'], $sql);
    $sql = preg_replace('/^(--[^\n]*)$/m', '<span class="cmt">$1</span>', $sql);
    $sql = preg_replace("/'([^']*)'/", "<span class=\"str\">'$1'</span>", $sql);
    $kws = [
        'UNBOUNDED PRECEDING','CURRENT ROW','PARTITION BY','GROUP BY','ORDER BY',
        'IS NOT NULL','LEFT JOIN','RIGHT JOIN','INNER JOIN','NOT IN',
        'IS NULL','DENSE_RANK','ROW_NUMBER','TIMESTAMPDIFF',
        'SELECT','FROM','WHERE','JOIN','ON','AND','OR','NOT','EXISTS',
        'AS','HAVING','LIMIT','WITH','UNION','DISTINCT',
        'CASE','WHEN','THEN','ELSE','END','OVER','ROWS','BETWEEN','DESC','ASC',
        'COUNT','SUM','AVG','MAX','MIN','ROUND','RANK','LAG','LEAD',
        'CURDATE','CONCAT','NULL',
    ];
    foreach ($kws as $kw) {
        $pattern = '/(?<![a-zA-Z_])(' . preg_quote($kw, '/') . ')(?![a-zA-Z_])/i';
        $sql = preg_replace($pattern, '<span class="kw">$1</span>', $sql);
    }
    $sql = preg_replace('/\b(\d+(?:\.\d+)?)\b/', '<span class="num">$1</span>', $sql);
    return $sql;
}

function cellClass(?string $val): string {
    if ($val === null) return '';
    $v = strtolower($val);
    if (str_contains($v, 'premium'))        return 'cell-premium';
    if (str_contains($v, 'oro'))            return 'cell-oro';
    if (str_contains($v, 'estándar'))       return 'cell-estandar';
    if (str_contains($v, 'moroso'))         return 'cell-moroso';
    if ($v === 'activo')                    return 'cell-activo';
    if ($v === 'bajo')                      return 'cell-bajo';
    if ($v === 'alto' || $v === 'muy alto') return 'cell-alto';
    if ($v === 'medio')                     return 'cell-medio';
    return '';
}

function runQuery(PDO $pdo, string $sql): array {
    $clean = preg_replace('/--[^\n]*/', '', $sql);
    $clean = trim($clean);
    if (empty($clean)) return ['columns'=>[],'rows'=>[],'error'=>null,'count'=>0];
    try {
        $stmt = $pdo->query($clean);
        $rows = $stmt->fetchAll();
        $cols = $rows ? array_keys($rows[0]) : [];
        return ['columns'=>$cols,'rows'=>$rows,'error'=>null,'count'=>count($rows)];
    } catch (PDOException $e) {
        return ['columns'=>[],'rows'=>[],'error'=>$e->getMessage(),'count'=>0];
    }
}

// ── Consultas ───────────────────────────────────────────────
$queries = [

    // ── BLOQUE A: CASE ──────────────────────────────────────
    [
        'titulo'      => 'A1 — Oferta de tarjeta según saldo',
        'bloque'      => 'CASE / Clasificación',
        'icono'       => '💳',
        'sql'         => "SELECT c.id_cuenta,
       CONCAT(cl.nombre,' ',cl.apellidos) AS cliente,
       c.tipo_cuenta,
       c.saldo,
       CASE
           WHEN c.saldo > 50000                  THEN 'Tarjeta Premium'
           WHEN c.saldo BETWEEN 10000 AND 50000  THEN 'Tarjeta Oro'
           ELSE                                       'Tarjeta Estándar'
       END AS oferta_recomendada
FROM Cuentas c
JOIN Clientes cl ON c.id_cliente = cl.id_cliente
WHERE c.activa = 1
LIMIT 20",
        'explicacion' => '<strong>CASE WHEN</strong> evalúa condiciones de arriba a abajo y devuelve el primer resultado verdadero, como un <em>if/else if</em>. El orden importa: si pusieras primero <code>saldo &gt; 10000</code>, los clientes con 60.000 € recibirían "Tarjeta Oro" en lugar de "Premium".',
    ],

    [
        'titulo'      => 'A2 — Clasificación multiproducto (tarjeta + fondo + seguro)',
        'bloque'      => 'CASE / Clasificación',
        'icono'       => '📦',
        'sql'         => "SELECT cl.id_cliente,
       CONCAT(cl.nombre,' ',cl.apellidos) AS cliente,
       cl.segmento,
       c.saldo,
       TIMESTAMPDIFF(YEAR, cl.fecha_nacimiento, CURDATE()) AS edad,
       CASE
           WHEN c.saldo > 50000 THEN 'Tarjeta Premium'
           WHEN c.saldo > 10000 THEN 'Tarjeta Oro'
           ELSE 'Tarjeta Estándar'
       END AS tarjeta,
       CASE
           WHEN sc.propension_inversion = 1 AND c.saldo > 5000 THEN 'Fondo Renta Variable'
           WHEN sc.propension_ahorro    = 1 AND c.saldo > 1000 THEN 'Fondo Renta Fija'
           ELSE '—'
       END AS fondo,
       CASE
           WHEN sc.propension_seguro = 1 THEN 'Seguro Vida Premium'
           ELSE 'Seguro Hogar'
       END AS seguro
FROM Clientes cl
JOIN Cuentas      c  ON cl.id_cliente = c.id_cliente AND c.activa = 1
JOIN ScoreCliente sc ON cl.id_cliente = sc.id_cliente
WHERE cl.activo = 1
LIMIT 20",
        'explicacion' => 'Varios <strong>CASE</strong> independientes en el mismo SELECT recomiendan distintos productos en una sola pasada. El resultado de uno no afecta a los demás. Patrón típico de motores de recomendación sencillos.',
    ],

    // ── BLOQUE B: WINDOW FUNCTIONS ───────────────────────────
    [
        'titulo'      => 'B1 — RANK vs ROW_NUMBER vs DENSE_RANK por sucursal',
        'bloque'      => 'Window Functions',
        'icono'       => '🏆',
        'sql'         => "SELECT 
       cl.id_sucursal,
       cl.id_cliente,
       cl.dni,
       cl.email,
       CONCAT(cl.nombre,' ',cl.apellidos) AS cliente,
       c.saldo,
       RANK()       OVER(PARTITION BY cl.id_sucursal ORDER BY c.saldo DESC) AS rnk,
       DENSE_RANK() OVER(PARTITION BY cl.id_sucursal ORDER BY c.saldo DESC) AS dense_rnk,
       ROW_NUMBER() OVER(PARTITION BY cl.id_sucursal ORDER BY c.saldo DESC) AS row_num
FROM Clientes cl
JOIN Cuentas c ON cl.id_cliente = c.id_cliente
WHERE c.activa = 1
ORDER BY cl.id_sucursal, c.saldo DESC
LIMIT 30",
        'explicacion' => '<strong>RANK</strong>: deja huecos en empates (1,1,3…). <strong>DENSE_RANK</strong>: sin huecos (1,1,2…). <strong>ROW_NUMBER</strong>: siempre único aunque haya empates (1,2,3…). <em>PARTITION BY</em> reinicia el contador por sucursal. Diferencia clave en entrevistas: si dos clientes empatan, RANK y DENSE_RANK les dan la misma posición; ROW_NUMBER no.',
    ],

    [
        'titulo'      => 'B2 — Top-3 clientes por saldo en cada provincia',
        'bloque'      => 'Window Functions',
        'icono'       => '🥇',
        'sql'         => "SELECT * FROM (
    SELECT cl.id_cliente,
           CONCAT(cl.nombre,' ',cl.apellidos) AS cliente,
           p.nombre AS provincia,
           c.saldo,
           RANK() OVER(PARTITION BY cl.id_provincia ORDER BY c.saldo DESC) AS ranking
    FROM Clientes cl
    JOIN Cuentas    c ON cl.id_cliente   = c.id_cliente
    JOIN Provincias p ON cl.id_provincia = p.id_provincia
    WHERE c.activa = 1
) ranked
WHERE ranking <= 3
ORDER BY provincia, ranking
LIMIT 30",
        'explicacion' => 'El filtro <code>WHERE ranking &lt;= 3</code> debe ir en una subconsulta porque las funciones de ventana se calculan <em>después</em> del WHERE. No puedes escribir <code>WHERE RANK() &lt;= 3</code> directamente: se ejecutaría antes de que la función de ventana exista.',
    ],

    [
        'titulo'      => 'B3 — Diferencia de saldo respecto al anterior (LAG)',
        'bloque'      => 'Window Functions',
        'icono'       => '📉',
        'sql'         => "SELECT cl.id_sucursal,
       CONCAT(cl.nombre,' ',cl.apellidos) AS cliente,
       c.saldo,
       LAG(c.saldo) OVER(PARTITION BY cl.id_sucursal ORDER BY c.saldo DESC) AS saldo_anterior,
       ROUND(
           c.saldo - LAG(c.saldo) OVER(PARTITION BY cl.id_sucursal ORDER BY c.saldo DESC)
       , 2) AS diferencia
FROM Clientes cl
JOIN Cuentas c ON cl.id_cliente = c.id_cliente
WHERE c.activa = 1
ORDER BY cl.id_sucursal, c.saldo DESC
LIMIT 25",
        'explicacion' => '<strong>LAG(col)</strong> accede al valor de la fila anterior dentro de la partición. Su opuesto es <strong>LEAD(col)</strong> (fila siguiente). La primera fila de cada partición devuelve NULL porque no hay fila previa. Muy útil para comparar periodos o posiciones consecutivas.',
    ],

    // ── BLOQUE C: JOINs ─────────────────────────────────────
    [
        'titulo'      => 'C1 — Clientes SIN préstamo activo (LEFT JOIN + IS NULL)',
        'bloque'      => 'JOINs',
        'icono'       => '🔗',
        'sql'         => "SELECT cl.id_cliente,
       CONCAT(cl.nombre,' ',cl.apellidos) AS cliente,
       cl.segmento,
       c.saldo
FROM Clientes cl
JOIN Cuentas c ON cl.id_cliente = c.id_cliente AND c.activa = 1
LEFT JOIN Prestamos p ON cl.id_cliente = p.id_cliente AND p.estado = 'Activo'
WHERE p.id_prestamo IS NULL
  AND cl.activo = 1
ORDER BY c.saldo DESC
LIMIT 25",
        'explicacion' => 'Patrón clásico para detectar ausencias: <strong>LEFT JOIN</strong> incluye todos los clientes aunque no tengan préstamo; el <code>IS NULL</code> filtra solo los que no cruzaron. Es más eficiente que <code>NOT IN (subquery)</code> y no tiene el problema de NULLs que afecta a NOT IN.',
    ],

    [
        'titulo'      => 'C2 — Sucursales con métricas (JOIN múltiple + agregación)',
        'bloque'      => 'JOINs',
        'icono'       => '🏦',
        'sql'         => "SELECT s.id_sucursal,
       s.nombre AS sucursal,
       pr.nombre AS provincia,
       COUNT(DISTINCT cl.id_cliente) AS num_clientes,
       ROUND(AVG(cu.saldo), 2)       AS saldo_medio,
       ROUND(SUM(cu.saldo), 2)       AS saldo_total,
       COUNT(DISTINCT p.id_prestamo) AS prestamos_activos
FROM Sucursales s
JOIN Provincias pr ON s.id_provincia    = pr.id_provincia
LEFT JOIN Clientes cl ON cl.id_sucursal = s.id_sucursal AND cl.activo = 1
LEFT JOIN Cuentas  cu ON cu.id_cliente  = cl.id_cliente AND cu.activa = 1
LEFT JOIN Prestamos p ON p.id_cliente   = cl.id_cliente AND p.estado  = 'Activo'
GROUP BY s.id_sucursal, s.nombre, pr.nombre
ORDER BY saldo_total DESC",
        'explicacion' => 'Con varios LEFT JOIN hay que usar <strong>COUNT(DISTINCT)</strong> para evitar duplicados: si un cliente tiene 2 cuentas y 1 préstamo, el JOIN genera 2 filas y un COUNT simple contaría ese cliente dos veces. DISTINCT garantiza contar cada entidad una sola vez.',
    ],

    // ── BLOQUE D: Subconsultas ───────────────────────────────
    [
        'titulo'      => 'D1 — Clientes con saldo superior a la media de su segmento',
        'bloque'      => 'Subconsultas',
        'icono'       => '📊',
        'sql'         => "SELECT cl.id_cliente,
       CONCAT(cl.nombre,' ',cl.apellidos) AS cliente,
       cl.segmento,
       ROUND(c.saldo, 2) AS saldo,
       ROUND((
           SELECT AVG(cu2.saldo)
           FROM Cuentas cu2
           JOIN Clientes cl2 ON cu2.id_cliente = cl2.id_cliente
           WHERE cl2.segmento = cl.segmento
       ), 2) AS media_segmento
FROM Clientes cl
JOIN Cuentas c ON cl.id_cliente = c.id_cliente
WHERE c.saldo > (
    SELECT AVG(cu2.saldo)
    FROM Cuentas cu2
    JOIN Clientes cl2 ON cu2.id_cliente = cl2.id_cliente
    WHERE cl2.segmento = cl.segmento
)
AND cl.activo = 1
ORDER BY cl.segmento, c.saldo DESC
LIMIT 25",
        'explicacion' => 'Una <strong>subconsulta correlacionada</strong> se ejecuta una vez por cada fila del SELECT externo, referenciando valores de esa fila (<code>cl.segmento</code>). Es potente pero puede ser lenta con tablas grandes; en ese caso conviene reescribirla como CTE o JOIN con subquery agrupada.',
    ],

    // ── BLOQUE E: CTEs ───────────────────────────────────────
    [
        'titulo'      => 'E1 — CTE: clientes con alto saldo + su scoring',
        'bloque'      => 'CTEs',
        'icono'       => '🧮',
        'sql'         => "WITH saldo_total AS (
    SELECT id_cliente, ROUND(SUM(saldo), 2) AS total
    FROM Cuentas
    WHERE activa = 1
    GROUP BY id_cliente
),
top_clientes AS (
    SELECT cl.id_cliente,
           CONCAT(cl.nombre,' ',cl.apellidos) AS cliente,
           cl.segmento,
           st.total
    FROM Clientes cl
    JOIN saldo_total st ON cl.id_cliente = st.id_cliente
    WHERE st.total > 30000 AND cl.activo = 1
)
SELECT tc.*, sc.score_credito, sc.riesgo
FROM top_clientes tc
JOIN ScoreCliente sc ON tc.id_cliente = sc.id_cliente
ORDER BY tc.total DESC
LIMIT 25",
        'explicacion' => 'Las <strong>CTEs</strong> (WITH) son subconsultas con nombre que mejoran la legibilidad. Aquí encadenamos dos CTEs: la primera calcula el saldo total por cliente, la segunda filtra los clientes top, y el SELECT final añade el scoring crediticio.',
    ],

    [
        'titulo'      => 'E2 — CTE: distribución de riesgo con % sobre total',
        'bloque'      => 'CTEs',
        'icono'       => '⚖️',
        'sql'         => "WITH resumen AS (
    SELECT sc.riesgo,
           COUNT(*)               AS num_clientes,
           ROUND(AVG(c.saldo), 2) AS saldo_medio,
           ROUND(SUM(c.saldo), 2) AS saldo_total
    FROM ScoreCliente sc
    JOIN Cuentas c ON sc.id_cliente = c.id_cliente AND c.activa = 1
    GROUP BY sc.riesgo
)
SELECT riesgo, num_clientes, saldo_medio, saldo_total,
       ROUND(saldo_total * 100.0 / SUM(saldo_total) OVER(), 2) AS pct_saldo
FROM resumen
ORDER BY saldo_total DESC",
        'explicacion' => 'Combina CTE + función de ventana (<strong>SUM() OVER()</strong> sin PARTITION calcula el total global). El <code>pct_saldo</code> divide el saldo de cada segmento de riesgo entre el total. Sin la ventana necesitarías una subconsulta extra o una variable.',
    ],

    // ── BLOQUE F: Trampas ────────────────────────────────────
    [
        'titulo'      => 'F1 — WHERE vs HAVING (pregunta trampa)',
        'bloque'      => 'Trampas de Entrevista',
        'icono'       => '⚠️',
        'sql'         => "-- WHERE filtra FILAS antes de agrupar
SELECT cl.id_sucursal,
       COUNT(*) AS clientes_con_saldo_alto
FROM Clientes cl
JOIN Cuentas c ON cl.id_cliente = c.id_cliente
WHERE c.saldo > 20000
GROUP BY cl.id_sucursal
ORDER BY clientes_con_saldo_alto DESC
LIMIT 15",
        'explicacion' => '<strong>WHERE</strong> actúa <em>antes</em> del GROUP BY: filtra filas individuales. <strong>HAVING</strong> actúa <em>después</em>: filtra sobre el resultado de la agregación. Regla: si el filtro usa una función de agregado (COUNT, SUM, AVG…) → HAVING. Si filtra por columna normal → WHERE.',
    ],

    [
        'titulo'      => 'F2 — EXISTS vs IN (rendimiento y NULLs)',
        'bloque'      => 'Trampas de Entrevista',
        'icono'       => '🔍',
        'sql'         => "-- EXISTS: más eficiente, para en el primer match
SELECT cl.id_cliente,
       CONCAT(cl.nombre,' ',cl.apellidos) AS cliente,
       cl.segmento
FROM Clientes cl
WHERE EXISTS (
    SELECT 1
    FROM Prestamos p
    WHERE p.id_cliente = cl.id_cliente
      AND p.estado = 'Moroso'
)
AND cl.activo = 1
ORDER BY cl.id_cliente
LIMIT 20",
        'explicacion' => '<strong>IN</strong> evalúa toda la sublista y falla si la subquery devuelve NULLs. <strong>EXISTS</strong> para en cuanto encuentra la primera coincidencia, es más eficiente con tablas grandes y no tiene el problema de NULLs. En MySQL moderno el optimizador suele igualarlos, pero la diferencia conceptual es clave en entrevistas.',
    ],

    // ── BLOQUE G: Recomendación ──────────────────────────────
    [
        'titulo'      => 'G1 — Clientes candidatos a depósito (sin producto activo)',
        'bloque'      => 'Recomendación de Productos',
        'icono'       => '🎯',
        'sql'         => "SELECT cl.id_cliente,
       CONCAT(cl.nombre,' ',cl.apellidos) AS cliente,
       cl.segmento,
       ROUND(c.saldo, 2) AS saldo,
       sc.score_credito,
       CASE
           WHEN c.saldo >= 20000 THEN 'Depósito 24M — 3,80%'
           WHEN c.saldo >= 10000 THEN 'Depósito 12M — 3,20%'
           ELSE                       'Depósito  6M — 2,50%'
       END AS deposito_sugerido
FROM Clientes cl
JOIN Cuentas      c  ON cl.id_cliente = c.id_cliente  AND c.activa = 1
JOIN ScoreCliente sc ON cl.id_cliente = sc.id_cliente
WHERE c.saldo > 5000
  AND cl.activo = 1
  AND cl.id_cliente NOT IN (
      SELECT cp.id_cliente
      FROM ClienteProductos cp
      JOIN Productos p ON cp.id_producto = p.id_producto
      WHERE p.categoria = 'Depósito' AND cp.estado = 'Activo'
  )
ORDER BY c.saldo DESC
LIMIT 25",
        'explicacion' => 'Combina <strong>NOT IN (subconsulta)</strong> para excluir clientes que ya tienen el producto y <strong>CASE</strong> para asignar el depósito adecuado al saldo. Es el patrón típico de campañas de cross-selling: alto saldo + sin producto = oportunidad.',
    ],

    [
        'titulo'      => 'G2 — Scoring 360°: propensión múltiple + productos activos',
        'bloque'      => 'Recomendación de Productos',
        'icono'       => '🌐',
        'sql'         => "SELECT cl.id_cliente,
       CONCAT(cl.nombre,' ',cl.apellidos) AS cliente,
       cl.segmento,
       TIMESTAMPDIFF(YEAR, cl.fecha_nacimiento, CURDATE()) AS edad,
       ROUND(c.saldo, 2) AS saldo,
       sc.score_credito,
       sc.riesgo,
       (sc.propension_ahorro + sc.propension_inversion + sc.propension_seguro) AS total_propensiones,
       COUNT(cp.id_contrato) AS productos_activos
FROM Clientes cl
JOIN Cuentas         c  ON cl.id_cliente = c.id_cliente  AND c.activa = 1
JOIN ScoreCliente    sc ON cl.id_cliente = sc.id_cliente
LEFT JOIN ClienteProductos cp ON cl.id_cliente = cp.id_cliente AND cp.estado = 'Activo'
WHERE cl.activo = 1
GROUP BY cl.id_cliente, cl.nombre, cl.apellidos, cl.segmento, cl.fecha_nacimiento,
         c.saldo, sc.score_credito, sc.riesgo,
         sc.propension_ahorro, sc.propension_inversion, sc.propension_seguro
HAVING total_propensiones >= 2
ORDER BY total_propensiones DESC, c.saldo DESC
LIMIT 25",
        'explicacion' => '<strong>HAVING</strong> sobre un alias calculado en el SELECT (total_propensiones): funciona en MySQL pero no en todos los motores SQL. La alternativa portable es repetir la expresión en el HAVING. LEFT JOIN + GROUP BY + HAVING combina detección de propensión con recuento de productos ya contratados.',
    ],
];

// ── Ejecutar consultas ──────────────────────────────────────
$pdo     = getDB();
$results = [];
foreach ($queries as $q) {
    $results[] = runQuery($pdo, $q['sql']);
}

$bloques = array_unique(array_column($queries, 'bloque'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Práctica SQL — Banco</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg:           #0b1120;
    --bg-card:      #111827;
    --bg-card2:     #0f1929;
    --bg-code:      #0a1020;
    --border:       rgba(56,139,253,.18);
    --border-h:     rgba(56,139,253,.45);
    --accent:       #3b82f6;
    --accent-2:     #38bdf8;
    --accent-warn:  #f59e0b;
    --accent-red:   #ef4444;
    --accent-green: #22c55e;
    --text-main:    #e2e8f0;
    --text-dim:     #64748b;
    --text-mid:     #94a3b8;
    --tag-bg:       rgba(59,130,246,.12);
    --radius:       14px;
    --radius-sm:    8px;
    --mono:         'Space Mono', monospace;
    --sans:         'DM Sans', sans-serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body { font-family: var(--sans); background: var(--bg); color: var(--text-main); min-height: 100vh; line-height: 1.6; }

/* Layout */
.layout { display: flex; min-height: 100vh; }

/* Sidebar */
.sidebar {
    width: 260px; flex-shrink: 0;
    background: var(--bg-card); border-right: 1px solid var(--border);
    position: sticky; top: 0; height: 100vh; overflow-y: auto;
    padding: 0 0 2rem;
    scrollbar-width: thin; scrollbar-color: var(--border) transparent;
}
.sidebar-logo { padding: 1.5rem 1.25rem 1rem; border-bottom: 1px solid var(--border); margin-bottom: .75rem; }
.sidebar-logo-title { font-family: var(--mono); font-size: .85rem; color: var(--accent); letter-spacing: .06em; text-transform: uppercase; }
.sidebar-logo-sub { font-size: .72rem; color: var(--text-dim); margin-top: 2px; }
.sidebar-section { padding: .25rem 1rem .1rem 1.25rem; font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--text-dim); margin-top: .75rem; }
.sidebar-link {
    display: flex; align-items: center; gap: .6rem;
    padding: .45rem 1rem .45rem 1.25rem;
    color: var(--text-mid); text-decoration: none; font-size: .82rem;
    transition: background .15s, color .15s;
    border: none; background: none; width: 100%; text-align: left; cursor: pointer;
}
.sidebar-link:hover { background: rgba(59,130,246,.08); color: var(--text-main); }
.sidebar-link .ico { font-size: 1rem; width: 20px; text-align: center; }

/* Main */
.main { flex: 1; overflow-x: hidden; padding: 2rem 2.5rem 4rem; max-width: 1400px; }

/* Header */
.page-header { margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border); }
.page-header h1 { font-family: var(--mono); font-size: 1.6rem; color: var(--text-main); }
.page-header h1 span { color: var(--accent); }
.page-header p { color: var(--text-mid); font-size: .9rem; margin-top: .4rem; }
.header-tags { display: flex; gap: .5rem; flex-wrap: wrap; margin-top: .9rem; }
.tag {
    display: inline-block; padding: .2rem .7rem;
    background: var(--tag-bg); border: 1px solid var(--border);
    border-radius: 100px; font-size: .72rem; font-family: var(--mono);
    color: var(--accent-2); letter-spacing: .04em;
}

/* Bloque título */
.bloque-titulo {
    font-family: var(--mono); font-size: .75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .1em; color: var(--text-dim);
    margin: 2.5rem 0 .9rem; display: flex; align-items: center; gap: .6rem;
}
.bloque-titulo::after { content: ''; flex: 1; height: 1px; background: var(--border); }

/* Query card */
.query-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius); margin-bottom: 1.5rem;
    overflow: hidden; transition: border-color .2s;
}
.query-card:hover { border-color: var(--border-h); }
.query-card-header {
    display: flex; align-items: center; gap: .75rem;
    padding: .9rem 1.25rem; border-bottom: 1px solid var(--border);
    background: var(--bg-card2);
}
.query-card-header .ico { font-size: 1.1rem; }
.query-card-header h3 { font-family: var(--mono); font-size: .85rem; font-weight: 700; color: var(--text-main); flex: 1; }
.query-count {
    font-family: var(--mono); font-size: .72rem; color: var(--text-dim);
    background: rgba(255,255,255,.04); border: 1px solid var(--border);
    border-radius: 100px; padding: .15rem .6rem;
}

/* Two-col body */
.query-body { display: grid; grid-template-columns: 1fr 1fr; min-height: 280px; }

/* Left panel */
.panel-left { border-right: 1px solid var(--border); display: flex; flex-direction: column; }
.panel-sql { flex: 1; border-bottom: 1px solid var(--border); padding: 1rem 1.25rem; background: var(--bg-code); }
.panel-label {
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .1em; color: var(--text-dim); margin-bottom: .6rem;
    display: flex; align-items: center; gap: .4rem;
}
.panel-label::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--accent); display: inline-block; }
pre.sql-code {
    font-family: var(--mono); font-size: .75rem; color: #93c5fd;
    white-space: pre-wrap; word-break: break-word; line-height: 1.75;
}
pre.sql-code .kw  { color: #f472b6; font-weight: 700; }
pre.sql-code .str { color: #86efac; }
pre.sql-code .num { color: #fde68a; }
pre.sql-code .cmt { color: #475569; font-style: italic; }

.panel-explain { padding: 1rem 1.25rem; background: var(--bg-card); min-height: 90px; }
.panel-explain p { font-size: .82rem; color: var(--text-mid); line-height: 1.65; }
.panel-explain strong { color: var(--accent-2); }
.panel-explain code { font-family: var(--mono); font-size: .75rem; background: rgba(59,130,246,.1); border: 1px solid rgba(59,130,246,.2); border-radius: 4px; padding: .1rem .35rem; color: #93c5fd; }
.panel-explain em { color: var(--accent-warn); font-style: normal; font-weight: 600; }

/* Right panel */
.panel-right { overflow: auto; padding: 1rem 1.25rem; max-height: 420px; }
.error-msg { font-family: var(--mono); font-size: .78rem; color: var(--accent-red); background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.2); border-radius: var(--radius-sm); padding: .75rem 1rem; }
.result-table { width: 100%; border-collapse: collapse; font-size: .76rem; white-space: nowrap; }
.result-table th {
    font-family: var(--mono); font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em; color: var(--accent);
    background: rgba(59,130,246,.07); padding: .5rem .75rem;
    border-bottom: 1px solid var(--border); text-align: left;
    position: sticky; top: 0;
}
.result-table td { padding: .4rem .75rem; border-bottom: 1px solid rgba(255,255,255,.04); color: var(--text-mid); font-family: var(--mono); font-size: .73rem; }
.result-table tr:last-child td { border-bottom: none; }
.result-table tr:hover td { background: rgba(59,130,246,.06); color: var(--text-main); }

.cell-premium  { color: #f59e0b !important; font-weight: 700; }
.cell-oro      { color: #fbbf24 !important; }
.cell-estandar { color: var(--text-dim) !important; }
.cell-moroso   { color: var(--accent-red) !important; font-weight: 700; }
.cell-activo   { color: var(--accent-green) !important; }
.cell-bajo     { color: var(--accent-green) !important; }
.cell-alto     { color: var(--accent-red) !important; }
.cell-medio    { color: var(--accent-warn) !important; }

.empty-msg { padding: 2rem; text-align: center; color: var(--text-dim); font-size: .82rem; }

::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(59,130,246,.25); border-radius: 10px; }

.footer { margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border); font-size: .76rem; color: var(--text-dim); display: flex; justify-content: space-between; flex-wrap: wrap; gap: .5rem; }

@media (max-width: 900px) {
    .query-body { grid-template-columns: 1fr; }
    .panel-left { border-right: none; border-bottom: 1px solid var(--border); }
    .sidebar { display: none; }
    .main { padding: 1.25rem 1rem 3rem; }
}
</style>
</head>
<body>
<div class="layout">

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-title">SQL Práctica</div>
        <div class="sidebar-logo-sub">Entrevista Banca · <?= count($queries) ?> consultas</div>
    </div>
    <?php
    $prev = '';
    foreach ($queries as $i => $q):
        if ($q['bloque'] !== $prev): $prev = $q['bloque']; ?>
    <div class="sidebar-section"><?= htmlspecialchars($q['bloque']) ?></div>
    <?php endif; ?>
    <a class="sidebar-link" href="#q<?= $i ?>">
        <span class="ico"><?= $q['icono'] ?></span>
        <?= htmlspecialchars($q['titulo']) ?>
    </a>
    <?php endforeach; ?>
</aside>

<!-- Main -->
<main class="main">
    <div class="page-header">
        <h1>SQL Práctica <span>Bancaria</span></h1>
        <p>Base de datos: <strong>admin_unicaja</strong> · <?= count($queries) ?> consultas de entrevista con resultado en tiempo real</p>
        <div class="header-tags">
            <?php foreach ($bloques as $b): ?>
            <span class="tag"><?= htmlspecialchars($b) ?></span>
            <?php endforeach; ?>
        </div>
    </div>

    <?php
    $prev = '';
    foreach ($queries as $i => $q):
        $res = $results[$i];
        if ($q['bloque'] !== $prev): $prev = $q['bloque']; ?>
    <div class="bloque-titulo"><?= htmlspecialchars($q['bloque']) ?></div>
    <?php endif; ?>

    <div class="query-card" id="q<?= $i ?>">
        <div class="query-card-header">
            <span class="ico"><?= $q['icono'] ?></span>
            <h3><?= htmlspecialchars($q['titulo']) ?></h3>
            <?php if (!$res['error']): ?>
            <span class="query-count"><?= $res['count'] ?> filas</span>
            <?php else: ?>
            <span class="query-count" style="color:var(--accent-red)">Error</span>
            <?php endif; ?>
        </div>

        <div class="query-body">
            <!-- Izquierda -->
            <div class="panel-left">
                <div class="panel-sql">
                    <div class="panel-label">Consulta SQL</div>
                    <pre class="sql-code"><?= highlightSQL($q['sql']) ?></pre>
                </div>
                <div class="panel-explain">
                    <div class="panel-label">Explicación</div>
                    <p><?= $q['explicacion'] ?></p>
                </div>
            </div>
            <!-- Derecha -->
            <div class="panel-right">
                <div class="panel-label">Resultado</div>
                <?php if ($res['error']): ?>
                <div class="error-msg">⚠ <?= htmlspecialchars($res['error']) ?></div>
                <?php elseif (empty($res['rows'])): ?>
                <div class="empty-msg">Sin resultados</div>
                <?php else: ?>
                <table class="result-table">
                    <thead><tr>
                        <?php foreach ($res['columns'] as $col): ?>
                        <th><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($res['rows'] as $row): ?>
                        <tr>
                            <?php foreach ($row as $val): ?>
                            <td class="<?= cellClass((string)$val) ?>"><?= htmlspecialchars((string)$val) ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php endforeach; ?>

    <div class="footer">
        <span>SQL Práctica Bancaria · <?= date('Y') ?></span>
        <span>admin_unicaja · <?= array_sum(array_column($results, 'count')) ?> filas devueltas en total</span>
    </div>
</main>

</div><!-- /layout -->
</body>
</html>
