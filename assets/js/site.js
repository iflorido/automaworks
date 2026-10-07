/* AutomaWorks — comportamiento mínimo de la web */
(function () {
  "use strict";

  /* Menú móvil */
  var toggle = document.querySelector(".nav-toggle");
  var menu = document.getElementById("menu");
  if (toggle && menu) {
    var close = function () {
      menu.classList.remove("is-open");
      toggle.setAttribute("aria-expanded", "false");
      toggle.textContent = "Menú";
    };
    toggle.addEventListener("click", function () {
      var open = menu.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", String(open));
      toggle.textContent = open ? "Cerrar" : "Menú";
    });
    menu.addEventListener("click", function (e) {
      if (e.target.closest("a")) close();
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && menu.classList.contains("is-open")) { close(); toggle.focus(); }
    });
    window.matchMedia("(min-width: 761px)").addEventListener("change", function (mq) {
      if (mq.matches) close();
    });
  }

  /* Formulario: lleva el foco al primer campo con error */
  var invalid = document.querySelector('.form [aria-invalid="true"]');
  if (invalid) invalid.focus();
})();
