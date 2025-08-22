<h2>📝 Reportar incidencia</h2>

<form method="post" action="/incidencias/public/incidents/store" enctype="multipart/form-data" style="display:grid;gap:10px;max-width:760px">
  <label>Título
    <input name="title" required style="width:100%">
  </label>

  <label>Descripción
    <textarea name="description" rows="4" required style="width:100%"></textarea>
  </label>

  <label>Fecha y hora del hecho
    <input type="datetime-local" name="occurred_at" value="<?= date('Y-m-d\TH:i') ?>">
  </label>

  <label>Provincia
    <select name="province_id">
      <option value="">—</option>
      <?php foreach($provinces as $p): ?>
        <option value="<?=$p['id']?>"><?= htmlspecialchars($p['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
    <label>Lat <input name="lat" placeholder="18.5"></label>
    <label>Lng <input name="lng" placeholder="-69.9"></label>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px">
    <label>Muertos <input type="number" name="dead" min="0"></label>
    <label>Heridos <input type="number" name="injured" min="0"></label>
    <label>Pérdida RD$ <input type="number" step="0.01" name="loss_rd"></label>
  </div>

  <label>Link a redes
    <input name="social_link" placeholder="https://...">
  </label>

  <fieldset>
    <legend>Tipo(s) de incidencia</legend>
    <?php foreach($types as $t): ?>
      <label style="display:inline-block;margin-right:12px">
        <input type="checkbox" name="type_ids[]" value="<?=$t['id']?>"> <?= ($t['icon']?:'•').' '.htmlspecialchars($t['name']) ?>
      </label>
    <?php endforeach; ?>
  </fieldset>

  <label>Foto
    <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">
  </label>

  <button>Guardar (queda pendiente)</button>
</form>
