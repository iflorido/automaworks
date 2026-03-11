// ── HELPERS ─────────────────────────────────────────────────────────────────
const fmt  = (n, d=0) => n.toLocaleString('es-ES', {minimumFractionDigits:d, maximumFractionDigits:d});
const fmtE = (n, d=0) => fmt(n,d) + ' €';
const fmtP = (n, d=2) => fmt(n,d) + ' %';
const clamp = (v,a,b) => Math.min(Math.max(v,a),b);
const $ = id => document.getElementById(id);

// ── EURIBOR EN TIEMPO REAL ────────────────────────────────────────────────────
// Fuente: BCE Data Portal SDMX REST API (data-api.ecb.europa.eu)
// Fallback: valor embebido actualizado mensualmente
let EURIBOR_VALUE = null;
let EURIBOR_DATE  = '';
const EURIBOR_FALLBACK      = 2.37;   // Euribor 12m Mar 2026
const EURIBOR_FALLBACK_DATE = '2026-03';

async function fetchEuribor() {
    const statusEl = $('euriborStatus');
    if (statusEl) statusEl.textContent = 'Cargando Euribor…';

    let result = null;
    try {
        const ctrl  = new AbortController();
        const timer = setTimeout(() => ctrl.abort(), 10000);
        const res   = await fetch(
            'https://data-api.ecb.europa.eu/service/data/FM/M.U2.EUR.RT.MM.EURIBOR1YD_.HSTA?format=jsondata&lastNObservations=1',
            { signal: ctrl.signal }
        );
        clearTimeout(timer);
        if (res.ok) {
            const j    = await res.json();
            const obs  = j.dataSets[0].series['0:0:0:0:0'].observations;
            const keys = Object.keys(obs).sort((a,b) => +a - +b);
            result = {
                value:  parseFloat(obs[keys[keys.length-1]][0]),
                date:   j.structure.dimensions.observation[0].values.slice(-1)[0]?.id || '',
                source: 'BCE·live'
            };
        }
    } catch(e) { /* red no disponible */ }

    if (result && !isNaN(result.value)) {
        EURIBOR_VALUE = result.value;
        EURIBOR_DATE  = result.date;
        if (statusEl) {
            statusEl.textContent = `Euribor 12m: ${fmtP(EURIBOR_VALUE,3)} · ${EURIBOR_DATE} · ${result.source}`;
            statusEl.className = 'euribor-badge live';
        }
    } else {
        EURIBOR_VALUE = EURIBOR_FALLBACK;
        EURIBOR_DATE  = EURIBOR_FALLBACK_DATE;
        if (statusEl) {
            statusEl.textContent = `Euribor 12m: ${fmtP(EURIBOR_VALUE,3)} · ${EURIBOR_DATE} · ref. actualizada`;
            statusEl.className = 'euribor-badge fallback';
        }
    }

    updateEuriborRate();
    if (_renderReady) render();
}

function getEuribor() {
    return EURIBOR_VALUE !== null ? EURIBOR_VALUE : EURIBOR_FALLBACK;
}

// ── STATE ────────────────────────────────────────────────────────────────────
const S = {
    capital:      150000,
    down:         30000,
    rate:         3.30,
    years:        25,
    openFee:      0.5,
    insurance:    35,
    notary:       2000,
    income:       3500,
    loanType:     'hipoteca',
    interestMode: 'fija',
    spread:       0.99,
    fixedYears:   5,
    fixedRate:    3.20,
};

// ── MODALIDADES DE INTERÉS ────────────────────────────────────────────────────
const INTEREST_MODES = {
    fija: {
        label: 'Fija',
        desc: 'Cuota constante durante toda la vida del préstamo. Sin riesgo ante subidas del Euribor. El tipo inicial es algo más alto que en variable.',
        showSpread: false, showFixedPeriod: false, euriborDriven: false,
        rateLabel: 'Tipo de interés nominal fijo (TIN)',
    },
    variable: {
        label: 'Variable (TIN libre)',
        desc: 'Introduces manualmente el tipo variable. La cuota se recalcularía cada año al revisar con el Euribor. Aquí puedes simular un escenario concreto.',
        showSpread: false, showFixedPeriod: false, euriborDriven: false,
        rateLabel: 'Tipo de interés variable (TIN actual)',
    },
    euribor_var: {
        label: 'Variable Euribor+',
        desc: 'Tipo = Euribor 12m oficial + diferencial negociado con el banco. Se revisa anualmente. La cuota puede subir o bajar según el mercado.',
        showSpread: true, showFixedPeriod: false, euriborDriven: true,
        rateLabel: 'Tipo resultante (Euribor + diferencial)',
    },
    mixta: {
        label: 'Mixta (fija→variable)',
        desc: 'Tipo fijo los primeros N años para tener certeza de cuota, luego pasa a variable libre. Muy ofertada en 2024-2026 por banca mediana y online.',
        showSpread: false, showFixedPeriod: true, euriborDriven: false,
        rateLabel: 'Tipo 2ª fase (variable libre)',
    },
    euribor_mix: {
        label: 'Mixta Euribor+',
        desc: 'Tipo fijo N años, luego Euribor + diferencial. Es la modalidad más habitual en los grandes bancos españoles hoy (CaixaBank, BBVA, Santander).',
        showSpread: true, showFixedPeriod: true, euriborDriven: true,
        rateLabel: 'Tipo 2ª fase (Euribor + diferencial)',
    },
};

// Defaults por tipo de préstamo y modalidad
const RATE_DEFAULTS = {
    hipoteca: {
        fija:3.30, variable:3.80, euribor_var:null, mixta:3.50, euribor_mix:null,
        spread:0.99, fixedYears:5, fixedRate:3.20,
    },
    personal: {
        fija:8.50, variable:8.50,
        spread:3.50, fixedYears:null, fixedRate:null,
    },
    coche: {
        fija:6.50, variable:6.50,
        spread:2.50, fixedYears:null, fixedRate:null,
    },
};

const PRESETS = {
    hipoteca: { capitalMin:10000, capitalMax:1000000, capitalDef:150000, downPct:0.2,
                rateDef:3.30, yearsMax:40, yearsDef:25, openFeeDef:0.5, insuranceDef:35, notaryDef:2000 },
    personal: { capitalMin:1000,  capitalMax:100000,  capitalDef:15000,  downPct:0,
                rateDef:8.50, yearsMax:10, yearsDef:5,  openFeeDef:1.0,  insuranceDef:15, notaryDef:0   },
    coche:    { capitalMin:2000,  capitalMax:150000,  capitalDef:25000,  downPct:0.1,
                rateDef:6.50, yearsMax:8,  yearsDef:5,  openFeeDef:0.5,  insuranceDef:20, notaryDef:300 },
};

// ── EURIBOR RATE SYNC ─────────────────────────────────────────────────────────
function updateEuriborRate() {
    const cfg = INTEREST_MODES[S.interestMode];
    if (!cfg || !cfg.euriborDriven) return;
    S.rate = Math.max(0.1, +(getEuribor() + S.spread).toFixed(3));
    const sl = $('rateSlider'), inp = $('rateInput');
    if (sl) { sl.value = S.rate; refreshSliderFill(sl); }
    if (inp) inp.value = S.rate.toLocaleString('es-ES',{minimumFractionDigits:2,maximumFractionDigits:2}) + ' %';
}

function refreshSliderFill(sl) {
    const pct = (sl.value - sl.min) / (sl.max - sl.min) * 100;
    sl.style.background = `linear-gradient(to right, var(--accent) ${pct}%, var(--border) ${pct}%)`;
}

// ── INTEREST MODE UI ──────────────────────────────────────────────────────────
function applyInterestModeUI() {
    const cfg = INTEREST_MODES[S.interestMode];
    if (!cfg) return;

    const get = id => $(id);

    get('interestModeDesc').textContent = cfg.desc;
    get('spreadRow').style.display      = cfg.showSpread       ? '' : 'none';
    get('fixedPeriodRow').style.display = cfg.showFixedPeriod  ? '' : 'none';
    get('fixedRateRow').style.display   = cfg.showFixedPeriod  ? '' : 'none';
    get('rateLabelText').textContent    = cfg.rateLabel;

    const rateSl = get('rateSlider'), rateIn = get('rateInput');
    if (rateSl) rateSl.disabled = cfg.euriborDriven;
    if (rateIn) {
        rateIn.readOnly = cfg.euriborDriven;
        rateIn.style.opacity = cfg.euriborDriven ? '0.55' : '1';
        rateIn.title = cfg.euriborDriven ? 'Se calcula automáticamente: Euribor + diferencial' : '';
    }

    if (cfg.euriborDriven) updateEuriborRate();
}

// ── FINANCE CORE ─────────────────────────────────────────────────────────────
function calcMonthly(principal, annualRate, years) {
    if (annualRate <= 0) return principal / (years * 12);
    const r = annualRate / 100 / 12;
    const n = years * 12;
    return principal * r * Math.pow(1+r, n) / (Math.pow(1+r, n) - 1);
}

function calcTAE(principal, avgMonthly, years, openFee, insurance, notary) {
    const cashOut    = openFee / 100 * principal + notary;
    const netReceived= Math.max(1, principal - cashOut);
    const mReal      = avgMonthly + insurance;
    const n          = years * 12;
    let r = Math.max(0.0001, S.rate / 100 / 12);
    for (let i = 0; i < 120; i++) {
        const pv  = mReal * (1 - Math.pow(1+r,-n)) / r;
        const dpv = (mReal/r) * ((1-Math.pow(1+r,-n))/r - n*Math.pow(1+r,-n-1));
        if (Math.abs(dpv) < 1e-14) break;
        r -= (pv - netReceived) / dpv;
        if (r <= 0) { r = 0.0001; break; }
    }
    return (Math.pow(1+r,12) - 1) * 100;
}

function buildSchedule(principal, annualRate, years) {
    const r = annualRate / 100 / 12;
    const monthly = calcMonthly(principal, annualRate, years);
    let balance = principal;
    const rows = [];
    for (let i = 1; i <= years*12 && balance > 0.01; i++) {
        const interest = balance * r;
        const capital  = Math.min(monthly - interest, balance);
        balance = Math.max(0, balance - capital);
        rows.push({ month:i, quota: interest+capital, capital, interest, balance, phase:'fija' });
    }
    return rows;
}

function buildScheduleMixta(principal, fixedRate, fixedYears, varRate, totalYears) {
    const rows = [];
    let balance = principal;

    // Fase 1: fija
    const mFix = calcMonthly(principal, fixedRate, totalYears);
    const rFix = fixedRate / 100 / 12;
    for (let i = 1; i <= fixedYears*12 && balance > 0.01; i++) {
        const interest = balance * rFix;
        const capital  = Math.min(mFix - interest, balance);
        balance = Math.max(0, balance - capital);
        rows.push({ month:i, quota: interest+capital, capital, interest, balance, phase:'fija' });
    }

    // Fase 2: variable sobre saldo pendiente
    const yearsLeft = totalYears - fixedYears;
    if (yearsLeft > 0 && balance > 0.01) {
        const mVar = calcMonthly(balance, varRate, yearsLeft);
        const rVar = varRate / 100 / 12;
        const offset = fixedYears * 12;
        for (let i = 1; i <= yearsLeft*12 && balance > 0.01; i++) {
            const interest = balance * rVar;
            const capital  = Math.min(mVar - interest, balance);
            balance = Math.max(0, balance - capital);
            rows.push({ month: offset+i, quota: interest+capital, capital, interest, balance, phase:'variable' });
        }
    }
    return rows;
}

// ── RENDER ───────────────────────────────────────────────────────────────────
let _renderReady = false;

function render() {
    const { capital, down, rate, years, openFee, insurance, notary, income,
            interestMode, fixedYears, fixedRate } = S;
    const principal = Math.max(1000, capital - down);
    const isMixta   = interestMode === 'mixta' || interestMode === 'euribor_mix';
    const n         = years * 12;

    let schedule, monthly, monthlyPhase2 = null;

    if (isMixta && fixedYears < years && fixedYears > 0) {
        schedule = buildScheduleMixta(principal, fixedRate, fixedYears, rate, years);
        monthly  = calcMonthly(principal, fixedRate, years);
        // cuota estimada fase variable: usar saldo al inicio de esa fase
        const firstVar = schedule.find(r => r.phase === 'variable');
        if (firstVar) {
            const balanceAtSwitch = firstVar.balance + firstVar.capital;
            monthlyPhase2 = calcMonthly(balanceAtSwitch, rate, years - fixedYears);
        }
    } else {
        schedule = buildSchedule(principal, rate, years);
        monthly  = calcMonthly(principal, rate, years);
    }

    const monthlyTotal   = monthly + insurance;
    const totalCapital   = schedule.reduce((a,r) => a + r.capital,  0);
    const totalInterest  = schedule.reduce((a,r) => a + r.interest, 0);
    const totalQuotas    = schedule.reduce((a,r) => a + r.quota,    0);
    const openFeeAmt     = openFee / 100 * principal;
    const grandTotal     = totalQuotas + openFeeAmt + notary + insurance * n;
    const avgMonthly     = totalQuotas / n;
    const tae            = calcTAE(principal, avgMonthly, years, openFee, insurance, notary);

    // Hero
    $('monthlyPayment').textContent = fmtE(monthly, 2);
    if (isMixta && monthlyPhase2) {
        $('monthlyPaymentSub').innerHTML =
            `<span style="color:var(--accent-2)">Fase fija ${fixedYears}a → ${fmtE(monthly,2)}/mes</span>` +
            ` &nbsp;·&nbsp; ` +
            `<span style="color:var(--accent-warn)">Fase variable → ~${fmtE(monthlyPhase2,2)}/mes</span>` +
            `<br><span style="color:var(--text-dim)">+ ${fmtE(insurance,0)}/mes seguros</span>`;
    } else {
        $('monthlyPaymentSub').textContent =
            `+ ${fmtE(insurance,0)}/mes seguros = ${fmtE(monthlyTotal,2)} real`;
    }

    // Grid stats
    $('resCapital').textContent       = fmtE(principal);
    $('resTAE').textContent           = fmtP(tae, 2);
    $('resTotalInterest').textContent = fmtE(totalInterest, 0);
    $('resTotalCosts').textContent    = fmtE(openFeeAmt + notary, 0);
    $('resTotalCost').textContent     = fmtE(grandTotal, 0);
    $('resSaving').textContent        = down > 0 ? `−${fmtE(down)} entrada` : 'Sin entrada';

    // Bar
    const tot = principal + totalInterest + openFeeAmt + notary;
    $('barPrincipal').style.width = (principal   / tot * 100).toFixed(1) + '%';
    $('barInterest').style.width  = (totalInterest/ tot * 100).toFixed(1) + '%';
    $('barCosts').style.width     = ((openFeeAmt+notary) / tot * 100).toFixed(1) + '%';

    // Affordability
    const ratio = (monthlyTotal / income) * 100;
    $('affordPct').textContent = fmtP(ratio, 1);
    const fill = $('affordFill');
    fill.style.width      = clamp(ratio, 0, 100) + '%';
    fill.style.background = ratio<=25 ? 'var(--accent-2)' : ratio<=35 ? 'var(--accent-warn)' : 'var(--accent-red)';
    $('affordMsg').textContent = ratio<=25
        ? '✓ Excelente — cuota cómoda según criterios bancarios (recomendado < 35%).'
        : ratio<=35
        ? '⚠ Aceptable — rozas el límite del 35% que los bancos aplican para aprobar hipotecas.'
        : '✗ Riesgo alto — los bancos suelen rechazar si la cuota supera el 35% de ingresos netos.';

    // Tables & tips
    renderAnnualTable (schedule, years, insurance, isMixta);
    renderMonthlyTable(schedule, insurance, isMixta);
    renderTips(tae, ratio, down, capital, rate, years, totalInterest, principal,
               interestMode, fixedYears, fixedRate, monthly, monthlyPhase2);
}

// ── TABLES ───────────────────────────────────────────────────────────────────
function phaseCell(phase, isMixta) {
    if (!isMixta) return '';
    return phase === 'fija'
        ? `<td style="color:var(--accent-2);font-size:0.71rem;font-weight:700;letter-spacing:.05em">FIJA</td>`
        : `<td style="color:var(--accent-warn);font-size:0.71rem;font-weight:700;letter-spacing:.05em">VAR</td>`;
}

function renderAnnualTable(sched, years, insurance, isMixta) {
    let html = `<table><thead><tr>
        <th>Año</th>${isMixta?'<th>Fase</th>':''}
        <th>Cuota anual</th><th>Capital</th><th>Intereses</th><th>Saldo pte.</th>
    </tr></thead><tbody>`;
    for (let y = 1; y <= years; y++) {
        const sl = sched.slice((y-1)*12, y*12);
        if (!sl.length) break;
        const tQ = sl.reduce((a,r)=>a+r.quota,   0);
        const tC = sl.reduce((a,r)=>a+r.capital,  0);
        const tI = sl.reduce((a,r)=>a+r.interest, 0);
        const b  = sl[sl.length-1].balance;
        html += `<tr><td>${y}</td>${phaseCell(sl[0].phase,isMixta)}
            <td>${fmtE(tQ+insurance*12,0)}</td>
            <td class="capital-col">${fmtE(tC,0)}</td>
            <td class="interest-col">${fmtE(tI,0)}</td>
            <td class="balance-col">${fmtE(b,0)}</td></tr>`;
    }
    $('annualTableWrap').innerHTML = html + '</tbody></table>';
}

function renderMonthlyTable(sched, insurance, isMixta) {
    let html = `<table><thead><tr>
        <th>Mes</th>${isMixta?'<th>Fase</th>':''}
        <th>Cuota</th><th>Capital</th><th>Intereses</th><th>Saldo pte.</th>
    </tr></thead><tbody>`;
    sched.slice(0,12).forEach(r => {
        html += `<tr><td>${r.month}</td>${phaseCell(r.phase,isMixta)}
            <td>${fmtE(r.quota+insurance,2)}</td>
            <td class="capital-col">${fmtE(r.capital,2)}</td>
            <td class="interest-col">${fmtE(r.interest,2)}</td>
            <td class="balance-col">${fmtE(r.balance,0)}</td></tr>`;
    });
    $('monthlyTableWrap').innerHTML = html + '</tbody></table>';
}

// ── TIPS ─────────────────────────────────────────────────────────────────────
function renderTips(tae, ratio, down, capital, rate, years, totalInterest, principal,
                    interestMode, fixedYears, fixedRate, monthly, monthlyPhase2) {
    const eur  = getEuribor();
    const tips = [];

    // Euribor actual
    tips.push({type:'info', html:
        `<strong>Euribor 12m: ${fmtP(eur,3)} ${EURIBOR_DATE ? '('+EURIBOR_DATE+')' : ''}</strong> — ` +
        `Con diferencial +0.65% (banca online): <strong>${fmtP(eur+0.65,2)}</strong>. ` +
        `Con +0.99% (banca tradicional): <strong>${fmtP(eur+0.99,2)}</strong>. ` +
        `Siempre compara por <strong>TAE</strong> (tu simulación: ${fmtP(tae,2)}), nunca solo por TIN.`
    });

    // Consejo específico por modalidad
    const modeText = {
        fija:        `<strong>Hipoteca fija al ${fmtP(rate,2)}:</strong> Cuota garantizada sin sorpresas. Ideal si los tipos son bajos o si tu perfil es conservador. Hoy la fija está convergiendo con la variable.`,
        variable:    `<strong>Hipoteca variable al ${fmtP(rate,2)}:</strong> Si el Euribor baja, tu cuota bajará. Ten siempre un colchón para absorber posibles subidas de +1-2 puntos.`,
        euribor_var: `<strong>Variable Euribor+${fmtP(S.spread,2)}:</strong> Tipo actual ${fmtP(eur+S.spread,2)}. Revisión anual. Si el Euribor sube 1 punto, la cuota pasaría a ~${fmtE(calcMonthly(principal,rate+1,years),2)}/mes (+${fmtE(calcMonthly(principal,rate+1,years)-monthly,2)}).`,
        mixta:       `<strong>Hipoteca mixta fija→variable:</strong> ${fixedYears} años al ${fmtP(fixedRate,2)} (cuota fija ${fmtE(monthly,2)}/mes), luego variable libre. Útil si planeas amortizar capital en el periodo fijo.`,
        euribor_mix: `<strong>Hipoteca mixta fija→Euribor+ (la más ofertada en España 2025-26):</strong> ` +
                     `${fixedYears} años al ${fmtP(fixedRate,2)} fijo (${fmtE(monthly,2)}/mes), ` +
                     `luego Euribor+${fmtP(S.spread,2)} (~${fmtE(monthlyPhase2||monthly,2)}/mes hoy). ` +
                     `CaixaBank, BBVA y Santander la ofrecen como producto estrella.`,
    };
    tips.push({type:'info', html: modeText[interestMode] || ''});

    // Entrada
    if (down / capital < 0.2) {
        tips.push({type:'warn', html:
            `<strong>Entrada inferior al 20%:</strong> Los bancos financian máximo el 80% del valor de tasación. ` +
            `Con tu entrada actual (${fmtP(down/capital*100,1)}) es posible que necesites aval, ` +
            `seguro de impago hipotecario o garantía adicional.`
        });
    } else {
        tips.push({type:'info', html:
            `<strong>Entrada ≥ 20% ✓</strong> (${fmtP(down/capital*100,1)}): Cumples el mínimo bancario. ` +
            `Si la aumentas un 5% más ahorrarías ~${fmtE(totalInterest*0.08,0)} en intereses.`
        });
    }

    // Ratio endeudamiento
    if (ratio > 35) {
        tips.push({type:'warn', html:
            `<strong>Ratio cuota/ingresos del ${fmtP(ratio,1)} — por encima del 35%:</strong> ` +
            `El Banco de España y la EBA recomiendan no superar el 35% de ingresos netos. ` +
            `Opciones: ampliar plazo ${years<40?'hasta '+(years+5)+' años':'(ya en máximo)'}, ` +
            `aumentar la entrada o reducir el capital solicitado.`
        });
    }

    // Subida de tipos (solo para variables)
    if (interestMode === 'euribor_var' || interestMode === 'euribor_mix') {
        const c1 = calcMonthly(principal, rate+1, years);
        const c2 = calcMonthly(principal, rate+2, years);
        tips.push({type:'warn', html:
            `<strong>Escenario de subida del Euribor:</strong> ` +
            `+1 punto → cuota ~${fmtE(c1,2)}/mes (<span style="color:var(--accent-warn)">+${fmtE(c1-monthly,2)}</span>). ` +
            `+2 puntos → cuota ~${fmtE(c2,2)}/mes (<span style="color:var(--accent-red)">+${fmtE(c2-monthly,2)}</span>). ` +
            `Asegúrate de tener colchón para al menos +2 puntos.`
        });
    }

    // Amortización anticipada
    const balAm   = principal * 0.9;
    const intAm   = calcMonthly(balAm, rate, years) * years*12 - balAm;
    const savingAm = Math.max(0, totalInterest - intAm);
    tips.push({type:'info', html:
        `<strong>Amortización anticipada:</strong> Aportando un 10% extra (${fmtE(principal*0.1,0)}) ` +
        `en los primeros años podrías ahorrar ~${fmtE(savingAm*0.6,0)} en intereses. ` +
        `Ley 5/2019: comisión por amortización anticipada <em>0% tras 3 años</em> en hipotecas variables.`
    });

    // Seguros vinculados
    tips.push({type:'info', html:
        `<strong>Bonificaciones por productos vinculados:</strong> Muchos bancos rebajan el TIN ` +
        `en -0.10% a -0.30% si domicilias la nómina, contratas seguro de vida (~15-40 €/mes) o ` +
        `seguro multirriesgo hogar (~20-35 €/mes). Compara si el ahorro en cuota supera el coste del seguro.`
    });

    $('tipsContent').innerHTML = tips
        .filter(t => t.html)
        .map(t => `<div class="info-box ${t.type==='warn'?'warn':''}">${t.html}</div>`)
        .join('');
}

// ── SLIDER ↔ INPUT BINDING ────────────────────────────────────────────────────
function setupField(sliderId, inputId, key, formatter, parser) {
    const sl = $(sliderId), inp = $(inputId);
    if (!sl || !inp) return;
    const update = (fromSlider) => {
        if (fromSlider) {
            S[key] = parseFloat(sl.value);
            inp.value = formatter(S[key]);
        } else {
            const v = parser(inp.value);
            if (!isNaN(v)) { S[key] = v; sl.value = v; }
            inp.value = formatter(S[key]);
        }
        refreshSliderFill(sl);
        // If spread changes and euribor mode, recalculate rate
        if (key === 'spread') updateEuriborRate();
        render();
    };
    sl.addEventListener('input',   () => update(true));
    inp.addEventListener('blur',   () => update(false));
    inp.addEventListener('keydown', e => { if(e.key==='Enter') update(false); });
    update(true);
}

const parseNum = s => parseFloat(s.replace(/[^\d,.-]/g,'').replace(',','.'));
const fmtPct   = v => v.toLocaleString('es-ES',{minimumFractionDigits:2,maximumFractionDigits:2}) + ' %';

setupField('capitalSlider',    'capitalInput',    'capital',    v => fmtE(v,0),         parseNum);
setupField('downSlider',       'downInput',       'down',       v => fmtE(v,0),         parseNum);
setupField('rateSlider',       'rateInput',       'rate',       fmtPct,                 parseNum);
setupField('yearsSlider',      'yearsInput',      'years',      v => v+(v===1?' año':' años'), parseNum);
setupField('openFeeSlider',    'openFeeInput',    'openFee',    fmtPct,                 parseNum);
setupField('insuranceSlider',  'insuranceInput',  'insurance',  v => fmtE(v,0)+'/mes', parseNum);
setupField('notarySlider',     'notaryInput',     'notary',     v => fmtE(v,0),         parseNum);
setupField('incomeSlider',     'incomeInput',     'income',     v => fmtE(v,0),         parseNum);
setupField('spreadSlider',     'spreadInput',     'spread',     fmtPct,                 parseNum);
setupField('fixedYearsSlider', 'fixedYearsInput', 'fixedYears', v => v+(v===1?' año':' años'), parseNum);
setupField('fixedRateSlider',  'fixedRateInput',  'fixedRate',  fmtPct,                 parseNum);

// ── INTEREST MODE TOGGLE ──────────────────────────────────────────────────────
document.querySelectorAll('#interestModeToggle .toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('#interestModeToggle .toggle-btn').forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        S.interestMode = btn.dataset.mode;

        // Apply default rate for this mode
        const d = RATE_DEFAULTS[S.loanType];
        if (d) {
            const defRate = d[S.interestMode];
            if (defRate != null) {
                S.rate = defRate;
                const sl = $('rateSlider');
                if (sl) { sl.value = S.rate; refreshSliderFill(sl); }
                $('rateInput').value = fmtPct(S.rate);
            }
        }
        applyInterestModeUI();
        render();
    });
});

// ── LOAN TYPE TOGGLE ──────────────────────────────────────────────────────────
document.querySelectorAll('#loanTypeToggle .toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('#loanTypeToggle .toggle-btn').forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        S.loanType = btn.dataset.type;
        const p = PRESETS[S.loanType];
        const d = RATE_DEFAULTS[S.loanType];

        // Hide hipoteca-only modes for personal/coche
        const hipOnly = ['euribor_var','mixta','euribor_mix'];
        document.querySelectorAll('#interestModeToggle .toggle-btn').forEach(b => {
            b.style.display = (S.loanType!=='hipoteca' && hipOnly.includes(b.dataset.mode)) ? 'none' : '';
        });
        if (S.loanType!=='hipoteca' && hipOnly.includes(S.interestMode)) {
            S.interestMode = 'fija';
            document.querySelectorAll('#interestModeToggle .toggle-btn').forEach(b =>
                b.classList.toggle('active', b.dataset.mode==='fija'));
        }

        // Update ranges
        const capSl = $('capitalSlider');
        capSl.min = p.capitalMin; capSl.max = p.capitalMax;
        $('capitalMin').textContent = fmtE(p.capitalMin);
        $('capitalMax').textContent = fmtE(p.capitalMax);
        $('yearsSlider').max = p.yearsMax;
        $('yearsMax').textContent = p.yearsMax + ' años';
        $('downSlider').max = p.capitalMax * 0.3;
        $('downMax').textContent = fmtE(p.capitalMax * 0.3);

        // Apply preset values
        S.capital   = p.capitalDef;
        S.down      = Math.round(p.capitalDef * p.downPct);
        S.rate      = p.rateDef;
        S.years     = p.yearsDef;
        S.openFee   = p.openFeeDef;
        S.insurance = p.insuranceDef;
        S.notary    = p.notaryDef;
        if (d) {
            if (d.spread    != null) S.spread    = d.spread;
            if (d.fixedYears!= null) S.fixedYears= d.fixedYears;
            if (d.fixedRate != null) S.fixedRate = d.fixedRate;
        }

        // Sync all inputs/sliders
        [
            ['capitalSlider',   'capitalInput',   fmtE(S.capital)],
            ['downSlider',      'downInput',      fmtE(S.down)],
            ['rateSlider',      'rateInput',      fmtPct(S.rate)],
            ['yearsSlider',     'yearsInput',     S.years+(S.years===1?' año':' años')],
            ['openFeeSlider',   'openFeeInput',   fmtPct(S.openFee)],
            ['insuranceSlider', 'insuranceInput', fmtE(S.insurance)+'/mes'],
            ['notarySlider',    'notaryInput',    fmtE(S.notary)],
            ['spreadSlider',    'spreadInput',    fmtPct(S.spread)],
        ].forEach(([slId, inId, val]) => {
            const sl = $(slId), inp = $(inId);
            if (sl) { sl.value = parseNum(val); refreshSliderFill(sl); }
            if (inp) inp.value = val;
        });

        applyInterestModeUI();
        render();
    });
});

// ── TABS ─────────────────────────────────────────────────────────────────────
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
        btn.classList.add('active');
        $('tab-'+btn.dataset.tab).classList.add('active');
    });
});

// ── INIT ─────────────────────────────────────────────────────────────────────
_renderReady = true;
applyInterestModeUI();
render();
fetchEuribor();   // async — actualiza cuando el BCE responde
// ── EURIBOR CHART HISTORICO ──────────────────────────────────────────────────
var EURIBOR_HIST_DATA = {"1999-01":3.026,"1999-02":3.032,"1999-03":2.963,"1999-04":2.739,"1999-05":2.633,"1999-06":2.811,"1999-07":2.906,"1999-08":2.776,"1999-09":2.807,"1999-10":3.052,"1999-11":3.204,"1999-12":3.33,"2000-01":3.512,"2000-02":3.727,"2000-03":4.042,"2000-04":4.278,"2000-05":4.714,"2000-06":4.941,"2000-07":5.093,"2000-08":5.152,"2000-09":5.156,"2000-10":5.185,"2000-11":5.186,"2000-12":4.958,"2001-01":4.78,"2001-02":4.72,"2001-03":4.643,"2001-04":4.624,"2001-05":4.56,"2001-06":4.407,"2001-07":4.323,"2001-08":4.224,"2001-09":3.906,"2001-10":3.546,"2001-11":3.259,"2001-12":3.287,"2002-01":3.398,"2002-02":3.384,"2002-03":3.457,"2002-04":3.601,"2002-05":3.598,"2002-06":3.715,"2002-07":3.713,"2002-08":3.571,"2002-09":3.319,"2002-10":3.131,"2002-11":2.983,"2002-12":2.82,"2003-01":2.756,"2003-02":2.587,"2003-03":2.531,"2003-04":2.485,"2003-05":2.353,"2003-06":2.136,"2003-07":2.083,"2003-08":2.132,"2003-09":2.188,"2003-10":2.145,"2003-11":2.17,"2003-12":2.197,"2004-01":2.207,"2004-02":2.185,"2004-03":2.093,"2004-04":2.092,"2004-05":2.209,"2004-06":2.377,"2004-07":2.335,"2004-08":2.246,"2004-09":2.282,"2004-10":2.328,"2004-11":2.373,"2004-12":2.407,"2005-01":2.37,"2005-02":2.293,"2005-03":2.259,"2005-04":2.22,"2005-05":2.154,"2005-06":2.092,"2005-07":2.098,"2005-08":2.17,"2005-09":2.226,"2005-10":2.336,"2005-11":2.47,"2005-12":2.612,"2006-01":2.833,"2006-02":2.965,"2006-03":3.077,"2006-04":3.256,"2006-05":3.449,"2006-06":3.534,"2006-07":3.665,"2006-08":3.788,"2006-09":3.928,"2006-10":4.0,"2006-11":4.038,"2006-12":4.107,"2007-01":4.188,"2007-02":4.116,"2007-03":4.106,"2007-04":4.278,"2007-05":4.413,"2007-06":4.505,"2007-07":4.643,"2007-08":4.725,"2007-09":4.723,"2007-10":4.625,"2007-11":4.607,"2007-12":4.793,"2008-01":4.651,"2008-02":4.349,"2008-03":4.59,"2008-04":4.838,"2008-05":4.993,"2008-06":5.361,"2008-07":5.393,"2008-08":5.323,"2008-09":5.384,"2008-10":5.248,"2008-11":4.288,"2008-12":3.452,"2009-01":2.622,"2009-02":2.135,"2009-03":1.909,"2009-04":1.771,"2009-05":1.644,"2009-06":1.61,"2009-07":1.412,"2009-08":1.334,"2009-09":1.261,"2009-10":1.243,"2009-11":1.23,"2009-12":1.236,"2010-01":1.232,"2010-02":1.225,"2010-03":1.215,"2010-04":1.225,"2010-05":1.249,"2010-06":1.281,"2010-07":1.373,"2010-08":1.421,"2010-09":1.421,"2010-10":1.495,"2010-11":1.539,"2010-12":1.526,"2011-01":1.55,"2011-02":1.714,"2011-03":1.924,"2011-04":2.086,"2011-05":2.147,"2011-06":2.147,"2011-07":2.183,"2011-08":2.097,"2011-09":2.067,"2011-10":2.102,"2011-11":2.044,"2011-12":2.004,"2012-01":1.837,"2012-02":1.686,"2012-03":1.625,"2012-04":1.581,"2012-05":1.333,"2012-06":1.217,"2012-07":1.208,"2012-08":1.052,"2012-09":0.786,"2012-10":0.655,"2012-11":0.58,"2012-12":0.549,"2013-01":0.576,"2013-02":0.584,"2013-03":0.543,"2013-04":0.528,"2013-05":0.467,"2013-06":0.524,"2013-07":0.525,"2013-08":0.542,"2013-09":0.547,"2013-10":0.537,"2013-11":0.506,"2013-12":0.558,"2014-01":0.552,"2014-02":0.538,"2014-03":0.547,"2014-04":0.581,"2014-05":0.473,"2014-06":0.498,"2014-07":0.475,"2014-08":0.382,"2014-09":0.357,"2014-10":0.331,"2014-11":0.248,"2014-12":0.248,"2015-01":0.25,"2015-02":0.224,"2015-03":0.213,"2015-04":0.17,"2015-05":0.165,"2015-06":0.165,"2015-07":0.167,"2015-08":0.168,"2015-09":0.154,"2015-10":0.133,"2015-11":0.079,"2015-12":0.058,"2016-01":0.042,"2016-02":-0.008,"2016-03":-0.012,"2016-04":-0.013,"2016-05":-0.017,"2016-06":-0.028,"2016-07":-0.052,"2016-08":-0.056,"2016-09":-0.069,"2016-10":-0.073,"2016-11":-0.073,"2016-12":-0.082,"2017-01":-0.083,"2017-02":-0.106,"2017-03":-0.113,"2017-04":-0.119,"2017-05":-0.127,"2017-06":-0.154,"2017-07":-0.153,"2017-08":-0.165,"2017-09":-0.173,"2017-10":-0.178,"2017-11":-0.188,"2017-12":-0.188,"2018-01":-0.186,"2018-02":-0.189,"2018-03":-0.19,"2018-04":-0.193,"2018-05":-0.19,"2018-06":-0.181,"2018-07":-0.181,"2018-08":-0.165,"2018-09":-0.166,"2018-10":-0.168,"2018-11":-0.148,"2018-12":-0.135,"2019-01":-0.121,"2019-02":-0.109,"2019-03":-0.112,"2019-04":-0.118,"2019-05":-0.134,"2019-06":-0.19,"2019-07":-0.27,"2019-08":-0.356,"2019-09":-0.422,"2019-10":-0.349,"2019-11":-0.348,"2019-12":-0.257,"2020-01":-0.248,"2020-02":-0.244,"2020-03":-0.188,"2020-04":-0.27,"2020-05":-0.083,"2020-06":-0.148,"2020-07":-0.274,"2020-08":-0.326,"2020-09":-0.415,"2020-10":-0.47,"2020-11":-0.481,"2020-12":-0.497,"2021-01":-0.502,"2021-02":-0.497,"2021-03":-0.487,"2021-04":-0.487,"2021-05":-0.484,"2021-06":-0.484,"2021-07":-0.487,"2021-08":-0.498,"2021-09":-0.49,"2021-10":-0.477,"2021-11":-0.461,"2021-12":-0.493,"2022-01":-0.499,"2022-02":-0.472,"2022-03":-0.237,"2022-04":0.013,"2022-05":0.256,"2022-06":0.852,"2022-07":1.004,"2022-08":1.249,"2022-09":2.107,"2022-10":2.629,"2022-11":2.728,"2022-12":2.828,"2023-01":3.316,"2023-02":3.534,"2023-03":3.647,"2023-04":3.757,"2023-05":3.862,"2023-06":4.007,"2023-07":4.149,"2023-08":4.074,"2023-09":4.149,"2023-10":4.16,"2023-11":4.023,"2023-12":3.679,"2024-01":3.532,"2024-02":3.671,"2024-03":3.723,"2024-04":3.703,"2024-05":3.808,"2024-06":3.714,"2024-07":3.526,"2024-08":3.17,"2024-09":2.936,"2024-10":2.691,"2024-11":2.561,"2024-12":2.425,"2025-01":2.448,"2025-02":2.358,"2025-03":2.267,"2025-04":2.136,"2025-05":2.07,"2025-06":2.057,"2025-07":2.07,"2025-08":2.147,"2025-09":2.148,"2025-10":2.204,"2025-11":2.199,"2025-12":2.227,"2026-01":2.245,"2026-02":2.218,"2026-03":2.367};

(function() {
    var ALL_LABELS = [];
    var ALL_VALUES = [];
    var chartInstance = null;
    var activeYears = 10;

    function loadChartJS(cb) {
        if (window.Chart) { cb(); return; }
        var s = document.createElement('script');
        s.src = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js';
        s.onload = cb;
        document.head.appendChild(s);
    }

    async function fetchHistory() {
        // Intento en vivo: BCE Data Portal (CORS abierto desde navegador)
        try {
            var ctrl  = new AbortController();
            var timer = setTimeout(function() { ctrl.abort(); }, 10000);
            var res   = await fetch(
                'https://data-api.ecb.europa.eu/service/data/FM/M.U2.EUR.RT.MM.EURIBOR1YD_.HSTA?format=jsondata',
                { signal: ctrl.signal }
            );
            clearTimeout(timer);
            if (res.ok) {
                var j       = await res.json();
                var obs     = j.dataSets[0].series['0:0:0:0:0'].observations;
                var periods = j.structure.dimensions.observation[0].values;
                var keys    = Object.keys(obs).sort(function(a,b){ return +a - +b; });
                ALL_LABELS  = keys.map(function(k){ return periods[+k] ? periods[+k].id : k; });
                ALL_VALUES  = keys.map(function(k){ return parseFloat(obs[k][0]); });
                console.log('[Euribor Chart] BCE en vivo: ' + ALL_LABELS.length + ' meses');
                return true;
            }
        } catch(e) { /* sin red o CORS — usar embebidos */ }

        // Fallback: datos embebidos 1999-2026 (siempre disponibles)
        var keys   = Object.keys(EURIBOR_HIST_DATA).sort();
        ALL_LABELS = keys;
        ALL_VALUES = keys.map(function(k){ return EURIBOR_HIST_DATA[k]; });
        return true;
    }

    function filterByYears(years) {
        if (!years) return { labels: ALL_LABELS.slice(), values: ALL_VALUES.slice() };
        var cutoff = new Date();
        cutoff.setFullYear(cutoff.getFullYear() - years);
        var labels = [], values = [];
        for (var i = 0; i < ALL_LABELS.length; i++) {
            if (new Date(ALL_LABELS[i] + '-01') >= cutoff) {
                labels.push(ALL_LABELS[i]);
                values.push(ALL_VALUES[i]);
            }
        }
        return { labels: labels, values: values };
    }

    var MONTHS_ES = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
    function fmtLabel(ym) {
        var parts = ym.split('-');
        return (MONTHS_ES[parseInt(parts[1])-1] || parts[1]) + ' ' + parts[0];
    }

    function updateStats(values) {
        var last = values[values.length - 1];
        var max  = Math.max.apply(null, values);
        var min  = Math.min.apply(null, values);
        var avg  = values.reduce(function(a,b){return a+b;},0) / values.length;
        var fS   = function(v){ return v.toFixed(3).replace('.',',') + ' %'; };
        document.getElementById('statCurrent').textContent = fS(last);
        document.getElementById('statMax').textContent     = fS(max);
        document.getElementById('statMin').textContent     = fS(min);
        document.getElementById('statAvg').textContent     = fS(avg);
        var changeEl = document.getElementById('statChange');
        var prev12   = ALL_VALUES.length >= 13 ? ALL_VALUES[ALL_VALUES.length - 13] : null;
        if (prev12 !== null) {
            var delta = ALL_VALUES[ALL_VALUES.length - 1] - prev12;
            changeEl.textContent = (delta >= 0 ? '+' : '') + delta.toFixed(3).replace('.',',') + ' %';
            changeEl.style.color = delta > 0 ? 'var(--accent-warn)' : 'var(--accent-2)';
        }
        document.getElementById('chartStats').style.display = '';
    }

    function drawChart(years) {
        var d = filterByYears(years);
        if (!d.labels.length) return;

        var canvas = document.getElementById('euriborChart');
        var ctx    = canvas.getContext('2d');

        var showEvery = years <= 2 ? 1 : years <= 5 ? 3 : 12;
        var tickLabels = d.labels.map(function(l, i) {
            var m = parseInt(l.split('-')[1]);
            if (i === d.labels.length - 1) return fmtLabel(l);
            if (m === 1) return l.substring(0,4);
            if (showEvery <= 3 && m % showEvery === 1) return fmtLabel(l);
            return '';
        });

        if (chartInstance) { chartInstance.destroy(); chartInstance = null; }

        var zeroPlugin = {
            id: 'zeroLine',
            afterDraw: function(chart) {
                var sc = chart.scales.y;
                if (!sc) return;
                var y0 = sc.getPixelForValue(0);
                var ca = chart.chartArea;
                if (y0 < ca.top || y0 > ca.bottom) return;
                var c = chart.ctx;
                c.save();
                c.strokeStyle = 'rgba(255,255,255,0.1)';
                c.lineWidth   = 1;
                c.setLineDash([5, 5]);
                c.beginPath(); c.moveTo(ca.left, y0); c.lineTo(ca.right, y0); c.stroke();
                c.restore();
            }
        };

        chartInstance = new Chart(ctx, {
            type: 'line',
            plugins: [zeroPlugin],
            data: {
                labels: tickLabels,
                datasets: [{
                    label: 'Euribor 12m',
                    data: d.values,
                    borderColor: '#4a90e2',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#4a90e2',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    backgroundColor: function(ctx2) {
                        var chart = ctx2.chart;
                        var ca = chart.chartArea;
                        if (!ca) return 'rgba(74,144,226,0.08)';
                        var g = chart.ctx.createLinearGradient(0, ca.top, 0, ca.bottom);
                        g.addColorStop(0,    'rgba(74,144,226,0.32)');
                        g.addColorStop(0.65, 'rgba(74,144,226,0.07)');
                        g.addColorStop(1,    'rgba(74,144,226,0.0)');
                        return g;
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0b1622',
                        borderColor: '#233652',
                        borderWidth: 1,
                        titleColor: '#8294a8',
                        bodyColor: '#e8edf3',
                        titleFont: { family: "'Space Mono', monospace", size: 11 },
                        bodyFont: { family: "'Oxygen', sans-serif", size: 13, weight: '700' },
                        padding: 12,
                        callbacks: {
                            title: function(items) { return fmtLabel(d.labels[items[0].dataIndex]); },
                            label: function(item)  { return '  ' + item.raw.toFixed(3).replace('.',',') + ' %'; }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(35,54,82,0.5)', drawTicks: false },
                        ticks: {
                            color: '#4e6278',
                            font: { family: "'Space Mono', monospace", size: 10 },
                            maxRotation: 0, autoSkip: false
                        },
                        border: { color: '#233652' }
                    },
                    y: {
                        grid: { color: 'rgba(35,54,82,0.5)', drawTicks: false },
                        ticks: {
                            color: '#4e6278',
                            font: { family: "'Space Mono', monospace", size: 10 },
                            callback: function(v) { return v.toFixed(1).replace('.',',') + '%'; },
                            maxTicksLimit: 7
                        },
                        border: { color: '#233652' }
                    }
                }
            }
        });

        updateStats(d.values);
    }

    async function initChart() {
        var ok      = await fetchHistory();
        var loading = document.getElementById('chartLoading');
        var canvas  = document.getElementById('euriborChart');

        if (!ok || !ALL_VALUES.length) {
            if (loading) loading.textContent = 'No se pudieron cargar los datos del BCE.';
            return;
        }
        if (loading) loading.style.display = 'none';
        if (canvas)  canvas.style.display  = 'block';

        loadChartJS(function() {
            drawChart(activeYears);
            document.querySelectorAll('.chart-range-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.chart-range-btn').forEach(function(b){ b.classList.remove('active'); });
                    btn.classList.add('active');
                    activeYears = parseInt(btn.dataset.years) || 0;
                    drawChart(activeYears);
                });
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChart);
    } else {
        initChart();
    }
})();