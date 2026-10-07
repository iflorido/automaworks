<?php
require __DIR__ . '/../includes/contact-handler.php';

$page = [
    'title'       => 'Contacto | AutomaWorks',
    'description' => 'Cuéntame qué necesita tu empresa: software a medida, integraciones, automatización o inteligencia artificial. Respuesta personal de Ignacio Florido.',
    'path'        => '/contacto/',
    'nav'         => 'contacto',
    'assistant'   => true,
];
require __DIR__ . '/../includes/header.php';

$err = fn($k) => isset($errors[$k])
    ? '<p class="field__error" id="err-' . $k . '">' . aw_e($errors[$k]) . '</p>'
    : '';
$inv = fn($k) => isset($errors[$k]) ? ' aria-invalid="true" aria-describedby="err-' . $k . '"' : '';
$fld = fn($k) => isset($errors[$k]) ? ' data-error' : '';
?>

<header class="page-head">
  <div class="wrap">
    <h1>Cuéntame tu caso</h1>
    <p>No hace falta que sepas qué tecnología necesitas. Explica qué ocurre hoy y qué te gustaría que ocurriera; con eso puedo darte una primera valoración.</p>
  </div>
</header>

<section class="section" id="formulario" aria-label="Formulario de contacto">
  <div class="wrap contact">

    <div>
      <?php if ($sent): ?>
        <div class="notice notice--ok" role="status">
          <strong>Mensaje enviado.</strong>
          Lo leo personalmente y te respondo al correo que has indicado.
        </div>
      <?php else: ?>

        <?php if (!empty($errors)): ?>
          <div class="notice" role="alert" style="margin-bottom:1.5rem">
            <strong>El mensaje no se ha enviado.</strong>
            <?= aw_e($errors['general'] ?? 'Revisa los campos marcados.') ?>
          </div>
        <?php endif; ?>

        <form class="form" method="post" action="/contacto/#formulario" novalidate>
          <input type="hidden" name="token" value="<?= aw_e($_SESSION['aw_form_token'] ?? '') ?>">
          <div class="hp" aria-hidden="true">
            <label for="website">No rellenes este campo</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
          </div>

          <div class="form__row">
            <div class="field"<?= $fld('nombre') ?>>
              <label for="nombre">Nombre</label>
              <input id="nombre" name="nombre" type="text" autocomplete="name" required value="<?= aw_e($form['nombre']) ?>"<?= $inv('nombre') ?>>
              <?= $err('nombre') ?>
            </div>
            <div class="field"<?= $fld('email') ?>>
              <label for="email">Correo electrónico</label>
              <input id="email" name="email" type="email" autocomplete="email" required value="<?= aw_e($form['email']) ?>"<?= $inv('email') ?>>
              <?= $err('email') ?>
            </div>
          </div>

          <div class="form__row">
            <div class="field">
              <label for="empresa">Empresa <span class="hint">(opcional)</span></label>
              <input id="empresa" name="empresa" type="text" autocomplete="organization" value="<?= aw_e($form['empresa']) ?>">
            </div>
            <div class="field">
              <label for="telefono">Teléfono <span class="hint">(opcional)</span></label>
              <input id="telefono" name="telefono" type="tel" autocomplete="tel" value="<?= aw_e($form['telefono']) ?>">
            </div>
          </div>

          <div class="field">
            <label for="necesidad">¿Qué necesitas?</label>
            <select id="necesidad" name="necesidad">
              <?php foreach ($needs as $value => $label): ?>
                <option value="<?= aw_e($value) ?>"<?= $form['necesidad'] === $value ? ' selected' : '' ?>><?= aw_e($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="field"<?= $fld('mensaje') ?>>
            <label for="mensaje">Cuéntame la situación</label>
            <textarea id="mensaje" name="mensaje" required placeholder="Por ejemplo: cada pedido de la tienda online lo pasamos a mano al ERP y perdemos dos horas al día."<?= $inv('mensaje') ?>><?= aw_e($form['mensaje']) ?></textarea>
            <?= $err('mensaje') ?>
          </div>

          <div class="field"<?= $fld('consentimiento') ?>>
            <label class="check">
              <input type="checkbox" name="consentimiento" value="1" required<?= !empty($_POST['consentimiento']) ? ' checked' : '' ?><?= $inv('consentimiento') ?>>
              <span>Acepto que AutomaWorks use estos datos para responder a mi consulta, según la <a class="link" href="/privacidad/">política de privacidad</a>.</span>
            </label>
            <?= $err('consentimiento') ?>
          </div>

          <div>
            <button class="btn btn--ink" type="submit">Enviar mensaje</button>
          </div>

          <p class="legal-note">Responsable: Ignacio Florido Pérez (AutomaWorks). Finalidad: responder a tu consulta. Legitimación: tu consentimiento. Los datos no se ceden a terceros salvo obligación legal y se alojan en servidores dentro de la Unión Europea. Puedes ejercer tus derechos escribiendo a contacto@automaworks.es.</p>
        </form>
      <?php endif; ?>
    </div>

    <aside class="contact__aside" aria-label="Otras formas de contacto">
      <dl>
        <div>
          <dt>Correo</dt>
          <dd><a class="link" href="mailto:contacto@automaworks.es">contacto@automaworks.es</a></dd>
        </div>
        <div>
          <dt>Dónde</dt>
          <dd>El Puerto de Santa María (Cádiz). Trabajo en remoto para empresas de toda España.</dd>
        </div>
        <div>
          <dt>Quién responde</dt>
          <dd>Ignacio Florido, personalmente. Puedes ver su perfil en <a class="link" href="https://iflorido.es">iflorido.es</a> o en <a class="link" href="https://www.linkedin.com/in/ignacio-florido/">LinkedIn</a>.</dd>
        </div>
      </dl>
    </aside>

  </div>
</section>

<section class="section section--blue on-dark" id="asistente" aria-labelledby="asistente-titulo">
  <div class="wrap assistant">
    <div class="assistant__intro">
      <h2 id="asistente-titulo">¿Prefieres preguntar primero?</h2>
      <p>El asistente conoce las soluciones y los proyectos de AutomaWorks. Puede ayudarte a ordenar la idea antes de escribir.</p>
      <small>Las respuestas las genera una IA a partir de información de esta web y pueden contener imprecisiones.</small>
    </div>
    <div id="awa-root"></div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
