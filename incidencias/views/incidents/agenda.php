<h2>🗓️ <?= htmlspecialchars($title ?? 'Agenda') ?></h2>

<form method="get" style="margin-bottom:10px">
  <label>Rango:
    <select name="days" onchange="this.form.submit()">
      <?php foreach([7,14,30,60] as $d): ?>
        <option value="<?=$d?>" <?= (($_GET['days']??30)==$d?'selected':'') ?>>Próx. <?=$d?> días</option>
      <?php endforeach; ?>
    </select>
  </label>
  <noscript><button>Filtrar</button></noscript>
</form>

<?php if (empty($items)): ?>
  <p>No hay seguimientos programados en el rango.</p>
<?php else: ?>
<table>
  <thead><tr><th>ID</th><th>Título</th><th>Para</th><th>Estado</th><th>Acción</th></tr></thead>
  <tbody>
    <?php foreach($items as $it): ?>
      <tr>
        <td><?= $it['id'] ?></td>
        <td><?= htmlspecialchars($it['title']) ?></td>
        <td><?= $it['follow_up_at'] ?></td>
        <td><?= $it['status'] ?></td>
        <td>
          <form method="post" action="/incidencias/public/incidents/mark-done">
            <input type="hidden" name="id" value="<?= $it['id'] ?>">
            <button>Marcar hecho</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
