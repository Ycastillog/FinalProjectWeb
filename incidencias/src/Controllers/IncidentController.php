<?php
namespace App\Controllers;
use App\Models\Incidents;

class IncidentController {

  public function index(): void {
    $M = new Incidents();
    $filters = [
      'q' => $_GET['q'] ?? null,
      'province_id' => $_GET['province_id'] ?? null,
      'type_id' => $_GET['type_id'] ?? null,
      'from' => $_GET['from'] ?? null,
      'to' => $_GET['to'] ?? null,
    ];
    $items = $M->allWithFilters($filters);
    $title = "Incidencias";
    $provinces = $M->provinces();
    $types = $M->types();
    $view  = __DIR__ . '/../../views/incidents/list.php';
    include __DIR__ . '/../../views/layout.php';
  }

  public function createPage(): void {
    $M = new Incidents();
    $title = "Reportar incidencia";
    $provinces = $M->provinces();
    $types = $M->types();
    $view  = __DIR__ . '/../../views/incidents/form.php';
    include __DIR__ . '/../../views/layout.php';
  }

  public function store(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('Location: /incidencias/public/incidents'); exit;
    }

    $occurred = $_POST['occurred_at'] ?? '';
    $occurred = $occurred ? str_replace('T',' ',$occurred) : date('Y-m-d H:i:s');

    $follow = $_POST['follow_up_at'] ?? '';
    $follow = $follow ? str_replace('T',' ',$follow) : null;

    $photo=null;
    if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error']===UPLOAD_ERR_OK) {
      $ext=strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
      if (in_array($ext,['jpg','jpeg','png','webp'])) {
        $name='inc_'.date('Ymd_His').'_'.bin2hex(random_bytes(3)).'.'.$ext;
        @move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/../../public/uploads/'.$name);
        $photo=$name;
      }
    }

    $d = [
      'title'           => trim($_POST['title'] ?? ''),
      'description'     => trim($_POST['description'] ?? ''),
      'occurred_at'     => $occurred,
      'follow_up_at'    => $follow,
      'province_id'     => $_POST['province_id']     ?: null,
      'municipality_id' => $_POST['municipality_id'] ?: null,
      'barrio_id'       => $_POST['barrio_id']       ?: null,
      'lat'             => $_POST['lat']             ?: null,
      'lng'             => $_POST['lng']             ?: null,
      'dead'            => $_POST['dead']            ?: null,
      'injured'         => $_POST['injured']         ?: null,
      'loss_rd'         => $_POST['loss_rd']         ?: null,
      'social_link'     => $_POST['social_link']     ?: null,
      'photo'           => $photo,
      'type_ids'        => $_POST['type_ids'] ?? []
    ];

    if ($d['title']==='' || $d['description']===''){ echo "Título y descripción son obligatorios."; return; }

    $userId = $_SESSION['user_id'] ?? null;
    (new Incidents())->create($d, $userId);

    header('Location: /incidencias/public/incidents/agenda'); exit;
  }

  public function apiRecent(): void {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode((new Incidents())->recentApproved24h());
  }

  public function agenda(): void {
    $days = (int)($_GET['days'] ?? 30);
    $items = (new Incidents())->agendaUpcoming($days);
    $title = "Agenda de seguimientos (próximos $days días)";
    $view  = __DIR__ . '/../../views/incidents/agenda.php';
    include __DIR__ . '/../../views/layout.php';
  }

  public function markDone(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
    $id = (int)($_POST['id'] ?? 0);
    if ($id) (new Incidents())->setDone($id);
    header('Location: /incidencias/public/incidents/agenda'); exit;
  }
}
