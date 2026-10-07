<?php
/** Bloque del asistente. Se usa en Inicio y en Contacto. */
?>
<div class="assistant" id="asistente-chat" data-api="<?= e(AW_ASSISTANT_API) ?>">
  <div class="assistant__log" id="asistente-log" aria-live="polite">
    <p class="assistant__msg assistant__msg--bot">Hola. Cuéntame qué necesita tu empresa o qué proceso quieres mejorar, y te digo cómo se podría abordar.</p>
  </div>
  <div class="assistant__suggestions" id="asistente-sugerencias">
    <button type="button" class="assistant__chip">Quiero conectar mi tienda online con el ERP</button>
    <button type="button" class="assistant__chip">¿Cuánto cuesta una aplicación a medida?</button>
    <button type="button" class="assistant__chip">¿Qué podría hacer la IA en mi empresa?</button>
  </div>
  <form class="assistant__form" id="asistente-form" novalidate>
    <label class="visually-hidden" for="asistente-input">Escribe tu pregunta</label>
    <textarea id="asistente-input" rows="2" maxlength="1500" placeholder="Escribe tu pregunta" required></textarea>
    <button class="btn btn--flame" type="submit">Enviar</button>
  </form>
  <details class="assistant__contact">
    <summary>Dejar mis datos para que Ignacio me escriba</summary>
    <div class="assistant__contact-fields">
      <label>Nombre <input type="text" id="asistente-nombre" autocomplete="name"></label>
      <label>Email <input type="email" id="asistente-email" autocomplete="email"></label>
    </div>
  </details>
  <p class="assistant__note">Las conversaciones se guardan para mejorar el asistente. No escribas datos sensibles. <a href="/privacidad/#asistente">Privacidad</a></p>
</div>
<script src="<?= asset('/assets/js/asistente.js') ?>" defer></script>
