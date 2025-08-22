<h2>✅ Validar reportes pendientes</h2>
<?php if (empty($items)): ?>
  <p>No hay pendientes.</p>
<?php else: ?>
<table>
  <thead><tr><th>ID</th><th>Título</th><th>Fecha</th><th>Coordenadas</th><th>Acciones</th></tr></thead>
  <tbody>
    <?php foreach($items as $it): ?>
      <tr>
        <td><?= $it['id'] ?></td>
        <td><?= htmlspecialchars($it['title']) ?></td>
        <td><?= $it['occurred_at'] ?></td>
        <td><?= $it['lat'] ?>, <?= $it['lng'] ?></td>
        <td style="display:flex;gap:6px">
          <form method="post" action="/incidencias/public/super/approve"><input type="hidden" name="id" value="<?=$it['id']?>"><button>✔ Aprobar</button></form>
          <form method="post" action="/incidencias/public/super/reject"><input type="hidden" name="id" value="<?=$it['id']?>"><button>✖ Rechazar</button></form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
