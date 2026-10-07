</main>

<footer class="site-footer on-dark">
  <div class="wrap">
    <div class="site-footer__grid">
      <div>
        <a class="brand" href="/">
          <svg class="brand__mark" viewBox="0 0 20 20" aria-hidden="true">
            <rect x="0"  y="0"  width="12" height="9"  fill="#C9C1B1"/>
            <rect x="12" y="0"  width="8"  height="13" fill="#A35139"/>
            <rect x="0"  y="9"  width="12" height="11" fill="#FFB162"/>
            <rect x="12" y="13" width="8"  height="7"  fill="#EEE9DF"/>
          </svg>
          AutomaWorks
        </a>
        <p>Software a medida, integraciones, automatización e inteligencia artificial para empresas. Desde El Puerto de Santa María, en remoto para toda España.</p>
      </div>

      <div>
        <h2>Web</h2>
        <ul>
          <li><a href="/soluciones/">Soluciones</a></li>
          <li><a href="/proyectos/">Proyectos</a></li>
          <li><a href="/#quien">Quién hay detrás</a></li>
          <li><a href="/calculadora/">Calculadora de préstamos</a></li>
        </ul>
      </div>

      <div>
        <h2>Contacto</h2>
        <ul>
          <li><a href="/contacto/">Formulario de contacto</a></li>
          <li><a href="mailto:contacto@automaworks.es">contacto@automaworks.es</a></li>
          <li><a href="https://www.linkedin.com/in/ignacio-florido/" rel="noopener">LinkedIn</a></li>
          <li><a href="https://github.com/iflorido" rel="noopener">GitHub</a></li>
        </ul>
      </div>

      <div>
        <h2>Legal</h2>
        <ul>
          <li><a href="/aviso-legal/">Aviso legal</a></li>
          <li><a href="/privacidad/">Privacidad y cookies</a></li>
          <li><button type="button" class="footer-link-btn" data-cookie-settings>Configurar cookies</button></li>
        </ul>
      </div>
    </div>

    <div class="site-footer__bottom">
      <span>© <?= date('Y') ?> AutomaWorks</span>
      <span>Diseñado y desarrollado por <a href="https://iflorido.es">Ignacio Florido</a></span>
    </div>
  </div>
</footer>

<div class="cookie-banner on-dark" id="cookie-banner" role="dialog" aria-labelledby="cookie-title" aria-describedby="cookie-text" hidden>
  <div class="cookie-banner__inner">
    <div>
      <h2 class="cookie-banner__title" id="cookie-title">Cookies de medición</h2>
      <p id="cookie-text">Uso Google Analytics para saber cuántas personas visitan la web y qué páginas leen. Solo se activa si lo aceptas. <a class="link" href="/privacidad/#cookies">Más información</a></p>
    </div>
    <div class="cookie-banner__actions">
      <button type="button" class="btn btn--flame" data-consent="accept">Aceptar</button>
      <button type="button" class="btn btn--light" data-consent="reject">Rechazar</button>
    </div>
  </div>
</div>

<script src="/assets/js/cookies.js?v=<?= $asset_v ?>" defer></script>
<script src="/assets/js/site.js?v=<?= $asset_v ?>" defer></script>
<?php if (!empty($page['assistant'])): ?>
<script src="/assets/js/asistente.js?v=<?= $asset_v ?>" defer></script>
<?php endif; ?>
</body>
</html>
