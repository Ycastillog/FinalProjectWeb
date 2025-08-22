<?php
namespace App\Controllers;
use App\Models\Incidents;

class AdminController {
  public function panel(): void {
    $title="Panel /super";
    $view=__DIR__.'/../../views/admin/panel.php';
    include __DIR__.'/../../views/layout.php';
  }
  public function validateList(): void {
    $items=(new Incidents())->pending();
    $title="Validar reportes pendientes";
    $view=__DIR__.'/../../views/admin/validate_incidents.php';
    include __DIR__.'/../../views/layout.php';
  }
  public function approve(): void {
    $id=(int)($_POST['id']??0);
    if($id)(new Incidents())->approve($id);
    header('Location: /incidencias/public/super/validate'); exit;
  }
  public function reject(): void {
    $id=(int)($_POST['id']??0);
    if($id)(new Incidents())->reject($id);
    header('Location: /incidencias/public/super/validate'); exit;
  }
  public function merge(): void {
    $winner=(int)($_POST['winner_id']??0); $loser=(int)($_POST['loser_id']??0);
    if($winner && $loser && $winner!=$loser) (new Incidents())->merge($winner,$loser);
    header('Location: /incidencias/public/super'); exit;
  }
}
