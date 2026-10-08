/**
 * asistente.js — Asistente embebido de automaworks.es
 * Basado en chat-embed.js de iflorido.es, con el mismo contrato de API:
 *   POST { messages, session_id, name, email } → { reply }
 *
 * Requiere en el HTML: <div id="awa-root"></div>
 *
 * Cambia API_URL cuando el contenedor específico de AutomaWorks esté desplegado.
 * El contenedor debe permitir CORS desde https://automaworks.es
 */
(function () {
  "use strict";

  const API_URL = "https://automachat.automaworks.es/chat";
  const root = document.getElementById("awa-root");
  if (!root) return;

  if (!document.querySelector('script[src*="marked.min.js"]')) {
    const s = document.createElement("script");
    s.src = "https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.6/marked.min.js";
    document.head.appendChild(s);
  }

  const sessionId = "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, c => {
    const r = Math.random() * 16 | 0;
    return (c === "x" ? r : (r & 0x3 | 0x8)).toString(16);
  });

  const SUGGESTIONS = [
    "¿Se puede conectar mi tienda online con el ERP?",
    "¿Cómo se calcula el coste de una aplicación a medida?",
    "¿Qué proyectos parecidos al mío habéis hecho?",
    "¿Trabajáis con Dolibarr?",
  ];

  let visitorName = "", visitorEmail = "", introDone = false, sending = false;
  const history = [];

  const style = document.createElement("style");
  style.textContent = `
    #awa-root {
      --awa-bg: #EEE9DF; --awa-surface: #E3DDD1; --awa-line: rgba(27,38,50,.18);
      --awa-ink: #1B2632; --awa-muted: #56606C; --awa-user: #1B2632; --awa-on-user: #EEE9DF;
      --awa-accent: #A35139;
      color: var(--awa-ink);
    }
    #awa-root, #awa-root * { box-sizing: border-box; }
    .awa-panel {
      background: var(--awa-bg); border-radius: 2px; overflow: hidden;
      display: flex; flex-direction: column; height: clamp(440px, 64vh, 580px);
    }
    .awa-head {
      display: flex; align-items: center; gap: 12px; padding: 14px 18px;
      border-bottom: 1px solid var(--awa-line); flex-shrink: 0;
    }
    .awa-mark { width: 30px; height: 30px; flex: none; }
    .awa-head-name { font-weight: 600; font-size: 15px; line-height: 1.2; }
    .awa-head-sub  { font-size: 13px; color: var(--awa-muted); }

    .awa-msgs {
      flex: 1; min-height: 0; overflow-y: auto; padding: 18px;
      display: flex; flex-direction: column; gap: 12px; overscroll-behavior: contain;
      scrollbar-width: thin; scrollbar-color: var(--awa-line) transparent;
    }
    .awa-msg { max-width: 84%; padding: 11px 15px; font-size: 15px; line-height: 1.55; word-break: break-word; }
    .awa-msg.bot  { background: var(--awa-surface); align-self: flex-start; }
    .awa-msg.user { background: var(--awa-user); color: var(--awa-on-user); align-self: flex-end; white-space: pre-wrap; }
    .awa-msg.wait { color: var(--awa-muted); font-style: italic; }
    .awa-msg.bot p { margin: 0 0 8px; } .awa-msg.bot p:last-child { margin: 0; }
    .awa-msg.bot a { color: var(--awa-accent); text-decoration: underline; text-underline-offset: .2em; }
    .awa-msg.bot ul, .awa-msg.bot ol { padding-left: 1.2rem; margin: 6px 0; }
    .awa-msg.bot code { background: rgba(27,38,50,.08); padding: 1px 5px; font-size: 13.5px; }
    .awa-msg.bot h1, .awa-msg.bot h2, .awa-msg.bot h3 { font-size: 15px; font-weight: 600; margin: 8px 0 4px; }

    .awa-intro { align-self: stretch; padding: 14px; border: 1px solid var(--awa-line); display: grid; gap: 10px; }
    .awa-intro p { margin: 0; font-size: 14px; color: var(--awa-muted); }
    .awa-fields { display: flex; flex-wrap: wrap; gap: 8px; }
    .awa-field {
      flex: 1 1 180px; min-width: 0; font: inherit; font-size: 16px; color: var(--awa-ink);
      background: #F6F3EC; border: 1px solid var(--awa-line); border-radius: 2px; padding: 9px 11px;
    }
    .awa-actions { display: flex; flex-wrap: wrap; gap: 14px; align-items: center; }
    .awa-btn {
      font-family: inherit; font-size: 14px; font-weight: 500; background: var(--awa-ink); color: var(--awa-on-user);
      border: 0; border-radius: 2px; padding: 10px 16px; cursor: pointer;
    }
    .awa-btn:hover { background: var(--awa-accent); }
    .awa-skip { font: inherit; font-size: 14px; background: none; border: 0; padding: 4px 0; color: var(--awa-muted); text-decoration: underline; cursor: pointer; }
    .awa-skip:hover { color: var(--awa-ink); }

    .awa-chips { display: flex; flex-wrap: wrap; gap: 8px; padding: 0 18px 12px; flex-shrink: 0; }
    .awa-chip {
      font: inherit; font-size: 13.5px; text-align: left; color: var(--awa-ink);
      background: transparent; border: 1px solid var(--awa-ink); border-radius: 2px;
      padding: 7px 11px; cursor: pointer;
    }
    .awa-chip:hover { background: var(--awa-ink); color: var(--awa-on-user); }

    .awa-input-row { display: flex; gap: 8px; padding: 12px 14px; border-top: 1px solid var(--awa-line); align-items: flex-end; flex-shrink: 0; }
    .awa-input {
      flex: 1; font: inherit; font-size: 16px; line-height: 1.45; color: var(--awa-ink); resize: none;
      max-height: 100px; background: #F6F3EC; border: 1px solid var(--awa-line); border-radius: 2px; padding: 10px 12px;
    }
    .awa-send {
      flex: none; font-family: inherit; font-size: 14px; font-weight: 500; height: 44px; padding: 0 16px; border: 0; border-radius: 2px;
      background: var(--awa-ink); color: var(--awa-on-user); cursor: pointer;
    }
    .awa-send:hover:not(:disabled) { background: var(--awa-accent); }
    .awa-send:disabled { opacity: .45; cursor: default; }
    #awa-root :focus-visible { outline: 3px solid var(--awa-accent); outline-offset: 2px; }

    @media (max-width: 560px) {
      .awa-panel { height: clamp(420px, 72vh, 540px); }
      .awa-msgs { padding: 14px; }
      .awa-msg { max-width: 92%; }
    }
  `;
  document.head.appendChild(style);

  root.innerHTML = `
    <div class="awa-panel">
      <div class="awa-head">
        <svg class="awa-mark" viewBox="0 0 20 20" aria-hidden="true">
          <rect x="0" y="0" width="12" height="9" fill="#2C3B4D"/><rect x="12" y="0" width="8" height="13" fill="#A35139"/>
          <rect x="0" y="9" width="12" height="11" fill="#FFB162"/><rect x="12" y="13" width="8" height="7" fill="#1B2632"/>
        </svg>
        <div>
          <div class="awa-head-name">Asistente de AutomaWorks</div>
          <div class="awa-head-sub">Responde con la información de soluciones y proyectos</div>
        </div>
      </div>
      <div class="awa-msgs" id="awa-msgs" aria-live="polite"></div>
      <div class="awa-chips" id="awa-chips"></div>
      <div class="awa-input-row">
        <label class="visually-hidden" for="awa-input">Tu pregunta</label>
        <textarea class="awa-input" id="awa-input" rows="1" placeholder="Escribe tu pregunta…"></textarea>
        <button class="awa-send" id="awa-send" type="button">Enviar</button>
      </div>
    </div>`;

  const msgs  = root.querySelector("#awa-msgs");
  const input = root.querySelector("#awa-input");
  const send  = root.querySelector("#awa-send");
  const chips = root.querySelector("#awa-chips");

  function whenMarked(cb) {
    if (typeof marked !== "undefined") return cb();
    let n = 0;
    const t = setInterval(() => {
      if (typeof marked !== "undefined") { clearInterval(t); cb(); }
      else if (++n > 60) clearInterval(t);
    }, 50);
  }

  // Se escapa el HTML antes de interpretar markdown: el modelo no puede inyectar etiquetas.
  function render(div, text) {
    marked.use({ breaks: true, gfm: true });
    div.innerHTML = marked.parse(text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;"));
    div.querySelectorAll("a").forEach(a => {
      if (!/^https?:|^mailto:|^\//.test(a.getAttribute("href") || "")) a.removeAttribute("href");
      a.target = "_blank"; a.rel = "noopener noreferrer";
    });
  }

  function add(text, role) {
    const div = document.createElement("div");
    div.className = "awa-msg " + role;
    div.textContent = text;
    if (role.startsWith("bot") && !role.includes("wait")) whenMarked(() => render(div, text));
    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
    return div;
  }

  function showChips() {
    chips.innerHTML = "";
    SUGGESTIONS.forEach(q => {
      const b = document.createElement("button");
      b.type = "button"; b.className = "awa-chip"; b.textContent = q;
      b.addEventListener("click", () => { input.value = q; ask(); });
      chips.appendChild(b);
    });
  }

  function intro() {
    const box = document.createElement("div");
    box.className = "awa-intro";
    box.innerHTML = `
      <p>Si quieres que Ignacio pueda contactarte después, deja tu nombre y correo. Es opcional.</p>
      <div class="awa-fields">
        <input class="awa-field" id="awa-name" type="text" placeholder="Nombre" autocomplete="name" aria-label="Nombre (opcional)">
        <input class="awa-field" id="awa-email" type="email" placeholder="Correo" autocomplete="email" aria-label="Correo (opcional)">
      </div>
      <div class="awa-actions">
        <button type="button" class="awa-btn" data-save="1">Empezar</button>
        <button type="button" class="awa-skip" data-save="0">Preguntar sin dejar datos</button>
      </div>`;
    msgs.appendChild(box);

    const done = save => {
      if (save) {
        visitorName  = box.querySelector("#awa-name").value.trim();
        visitorEmail = box.querySelector("#awa-email").value.trim();
      }
      box.remove();
      introDone = true;
      showChips();
      input.focus({ preventScroll: true });
    };
    box.querySelectorAll("button").forEach(b => b.addEventListener("click", () => done(b.dataset.save === "1")));
    box.querySelectorAll("input").forEach(i => i.addEventListener("keydown", e => {
      if (e.key === "Enter") { e.preventDefault(); done(true); }
    }));
  }

  async function ask() {
    const text = input.value.trim();
    if (!text || sending) return;
    if (!introDone) { introDone = true; msgs.querySelector(".awa-intro")?.remove(); }

    chips.style.display = "none";
    sending = true; send.disabled = true;
    input.value = ""; input.style.height = "auto";

    add(text, "user");
    history.push({ role: "user", content: text });
    const wait = add("Consultando…", "bot wait");

    try {
      const res = await fetch(API_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ messages: history, session_id: sessionId, name: visitorName, email: visitorEmail }),
      });
      if (!res.ok) throw new Error("HTTP " + res.status);
      const data = await res.json();
      const reply = data.reply || "No he podido obtener respuesta.";
      wait.remove();
      add(reply, "bot");
      history.push({ role: "assistant", content: reply });
    } catch (e) {
      wait.remove();
      add("No hay conexión con el asistente. Prueba de nuevo en unos segundos o escribe a contacto@automaworks.es.", "bot wait");
      history.pop();
    }
    sending = false; send.disabled = false;
  }

  send.addEventListener("click", ask);
  input.addEventListener("keydown", e => {
    if (e.key === "Enter" && !e.shiftKey) { e.preventDefault(); ask(); }
  });
  input.addEventListener("input", () => {
    input.style.height = "auto";
    input.style.height = Math.min(input.scrollHeight, 100) + "px";
  });

  function start() {
    if (msgs.children.length) return;
    add("Hola. Soy el asistente de **AutomaWorks**. Conozco las soluciones, los proyectos y la forma de trabajar de Ignacio. Cuéntame qué necesita tu empresa o elige una de las preguntas.", "bot");
    intro();
  }

  if ("IntersectionObserver" in window) {
    const io = new IntersectionObserver(es => {
      if (es.some(e => e.isIntersecting)) { start(); io.disconnect(); }
    }, { threshold: .25 });
    io.observe(root);
  } else {
    start();
  }
})();
