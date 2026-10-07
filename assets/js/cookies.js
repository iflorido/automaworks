/**
 * cookies.js — Consentimiento y Google Analytics de automaworks.es
 *
 * Google Analytics NO se carga hasta que el visitante pulsa «Aceptar».
 * Si rechaza, o retira el consentimiento después, no se carga y se borran
 * las cookies _ga que pudieran existir.
 *
 * La decisión se guarda 12 meses en localStorage. Pasado ese plazo
 * se vuelve a preguntar.
 */
(function () {
  "use strict";

  var GA_ID = "G-C0XB04YH7T";
  var KEY = "aw_consent";
  var MAX_AGE = 365 * 24 * 60 * 60 * 1000;
  var banner = document.getElementById("cookie-banner");

  function read() {
    try {
      var data = JSON.parse(localStorage.getItem(KEY) || "null");
      if (!data || Date.now() - data.t > MAX_AGE) return null;
      return data.v;
    } catch (e) { return null; }
  }

  function save(value) {
    try { localStorage.setItem(KEY, JSON.stringify({ v: value, t: Date.now() })); } catch (e) {}
  }

  function loadAnalytics() {
    if (window.__awAnalytics) return;
    window.__awAnalytics = true;
    window["ga-disable-" + GA_ID] = false;

    var s = document.createElement("script");
    s.async = true;
    s.src = "https://www.googletagmanager.com/gtag/js?id=" + GA_ID;
    document.head.appendChild(s);

    window.dataLayer = window.dataLayer || [];
    window.gtag = function () { window.dataLayer.push(arguments); };
    window.gtag("js", new Date());
    window.gtag("config", GA_ID);
  }

  function removeAnalyticsCookies() {
    window["ga-disable-" + GA_ID] = true;
    var host = location.hostname;
    var domains = ["", host, "." + host, "." + host.replace(/^www\./, "")];
    document.cookie.split(";").forEach(function (c) {
      var name = c.split("=")[0].trim();
      if (name === "_ga" || name.indexOf("_ga_") === 0 || name === "_gid" || name === "_gat") {
        domains.forEach(function (d) {
          document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/" + (d ? "; domain=" + d : "");
        });
      }
    });
  }

  function show() {
    if (!banner) return;
    banner.hidden = false;
    var first = banner.querySelector("button");
    if (first) first.focus({ preventScroll: true });
  }

  function hide() { if (banner) banner.hidden = true; }

  function decide(value) {
    var before = read();
    save(value);
    hide();
    if (value === "accept") {
      loadAnalytics();
    } else {
      removeAnalyticsCookies();
      // Si ya estaba cargado, recargar garantiza que deja de medir.
      if (before === "accept" && window.__awAnalytics) location.reload();
    }
  }

  /* Botones del banner */
  if (banner) {
    banner.addEventListener("click", function (e) {
      var btn = e.target.closest("[data-consent]");
      if (btn) decide(btn.getAttribute("data-consent"));
    });
  }

  /* Enlaces «Configurar cookies» en el pie y en la política */
  document.addEventListener("click", function (e) {
    if (e.target.closest("[data-cookie-settings]")) { e.preventDefault(); show(); }
  });

  /* Arranque */
  var current = read();
  if (current === "accept") loadAnalytics();
  else if (current === null) show();
})();
