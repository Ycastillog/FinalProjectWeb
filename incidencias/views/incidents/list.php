<h2>📋 Incidencias</h2>

<form method="get" style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;margin-bottom:12px">
  <input type="text" name="q" placeholder="Buscar título/descr" value="<?= htmlspecialchars($_GET['q']??'') ?>">
  <select name="province_id">
    <option value="">Provincia</option>
    <?php foreach($provinces as $p): ?>
      <option value="<?=$p['id']?>" <?= (($_GET['province_id']??'')==$p['id'])?'selected':'' ?>>
        <?= htmlspecialchars($p['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <select name="type_id">
    <option value="">Tipo</option>
    <?php foreach($types as $t): ?>
      <option value="<?=$t['id']?>" <?= (($_GET['type_id']??'')==$t['id'])?'selected':'' ?>>
        <?= ($t['icon']?:'•').' '.htmlspecialchars($t['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <div></div>
  <input type="date" name="from" value="<?= htmlspecialchars($_GET['from']??'') ?>">
  <input type="date" name="to" value="<?= htmlspecialchars($_GET['to']??'') ?>">
  <button>Filtrar</button>
</form>

<p><a href="/incidencias/public/incidents/new">+ Nueva incidencia</a></p>

<?php if (empty($items)): ?>
  <p>No hay incidencias.</p>
<?php else: ?>
<table>
  <thead><tr><th>ID</th><th>Título</th><th>Tipos</th><th>Provincia</th><th>Muertos/Heridos</th><th>Fecha</th><th>Estado</th></tr></thead>
  <tbody>
  <?php foreach ($items as $it): ?>
    <tr>
      <td><?= $it['id'] ?></td>
      <td><?= htmlspecialchars($it['title']) ?></td>
      <td><?php
        if (!empty($it['types'])) echo implode(', ', array_map(fn($t)=>($t['icon']?:'•').' '.htmlspecialchars($t['name']), $it['types']));
        else echo '—';
      ?></td>
      <td><?= htmlspecialchars($it['province_name'] ?? '—') ?></td>
      <td><?= (int)($it['dead']??0) ?>/<?= (int)($it['injured']??0) ?></td>
      <td><?= $it['occurred_at'] ?></td>
      <td><?= $it['status'] ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
