<h2>🔐 /super</h2>
<ul>
  <li><a href="/incidencias/public/super/validate">Validar reportes pendientes</a></li>
  <li><a href="/incidencias/public/incidents">Ver incidencias</a></li>
</ul>

<h3>Unir duplicados</h3>
<form method="post" action="/incidencias/public/super/merge" style="display:flex;gap:8px;align-items:center">
  <input name="winner_id" placeholder="ID a conservar" required>
  <input name="loser_id"  placeholder="ID a unir" required>
  <button>Unir</button>
</form>
