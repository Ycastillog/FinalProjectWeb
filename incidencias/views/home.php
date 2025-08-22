<h1>🗺️ Incidencias (últimas 24 h, aprobadas)</h1>
<div id="map" style="height:520px;border:1px solid #ccc;border-radius:8px"></div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
const map = L.map('map').setView([18.5,-69.9], 8);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'© OSM'}).addTo(map);
const cluster = L.markerClusterGroup();
fetch('./api/incidents').then(r=>r.json()).then(rows=>{
  rows.forEach(r=>{
    if(!r.lat || !r.lng) return;
    const types = (r.types||[]).map(t=> (t.icon||'•')+' '+t.name).join(', ');
    const m = L.marker([r.lat, r.lng]);
    m.bindPopup(`<b>${r.title}</b><br>${types||'—'}<br>
      Muertos: ${r.dead ?? '—'} | Heridos: ${r.injured ?? '—'}<br>
      Pérdida: ${r.loss_rd ?? '—'} RD$<br>${r.occurred_at}`);
    cluster.addLayer(m);
  });
  map.addLayer(cluster);
});
</script>
