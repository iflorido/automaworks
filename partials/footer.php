</main>
<footer class="site-footer">
  <div class="site-footer__grid">
    <div class="site-footer__brand">
      <a class="wordmark wordmark--light" href="/">AutomaWorks</a>
      <p>Software a medida, integraciones, automatización e inteligencia artificial para empresas. Desde El Puerto de Santa María, en remoto para toda España.</p>
    </div>
    <nav class="site-footer__col" aria-label="Páginas">
      <h2 class="site-footer__title">Páginas</h2>
      <a href="/soluciones/">Soluciones</a>
      <a href="/proyectos/">Proyectos</a>
      <a href="/contacto/">Contacto</a>
      <a href="/calculadora/">Calculadora de préstamos</a>
    </nav>
    <div class="site-footer__col">
      <h2 class="site-footer__title">Contacto</h2>
      <?= aw_email_link() ?>
      <a href="https://iflorido.es" rel="noopener">Ignacio Florido</a>
      <a href="https://www.linkedin.com/in/ignacio-florido/" rel="noopener">LinkedIn</a>
      <a href="https://github.com/iflorido" rel="noopener">GitHub</a>
    </div>
    <div class="site-footer__col">
      <h2 class="site-footer__title">Legal</h2>
      <a href="/aviso-legal/">Aviso legal</a>
      <a href="/privacidad/">Privacidad y cookies</a>
      <button class="link-button js-cookie-settings" type="button">Configurar cookies</button>
    </div>
  </div>
  <p class="site-footer__legal">© <?= date('Y') ?> AutomaWorks. Ignacio Florido Pérez.</p>
</footer>
<div class="consent" id="consent" role="region" aria-label="Cookies" hidden>
  <p>Esta web usa Google Analytics para contar visitas de forma agregada, solo si lo aceptas. <a href="/privacidad/#cookies">Más información</a></p>
  <div class="consent__actions">
    <button class="btn btn--ghost js-consent" type="button" data-value="rejected">Rechazar</button>
    <button class="btn btn--dark js-consent" type="button" data-value="accepted">Aceptar</button>
  </div>
</div>
</body>
</html>
