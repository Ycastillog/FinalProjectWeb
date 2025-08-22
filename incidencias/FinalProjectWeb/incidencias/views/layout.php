<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title ?? 'Incidencias RD') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Arial;margin:20px}
    a{color:#0a58ca;text-decoration:none}
    nav a{margin-right:12px}
    .container{max-width:980px;margin:auto}
    table{width:100%;border-collapse:collapse}
    th,td{border:1px solid #ddd;padding:8px}
    th{background:#f5f5f5}
    hr{margin:12px 0}
  </style>
</head>
<body>
  <div class="container">
    <nav>
      <a href="/incidencias/public/">Inicio</a>
      <a href="/incidencias/public/incidents">Incidencias</a>
      <a href="/incidencias/public/incidents/new">Reportar</a>
      <a href="/incidencias/public/super">/super</a>
    </nav>
    <hr>
    <?php if(isset($view)&&is_file($view)) include $view; else echo "<p>⚠️ Vista no encontrada.</p>"; ?>
  </div>
</body>
</html>
