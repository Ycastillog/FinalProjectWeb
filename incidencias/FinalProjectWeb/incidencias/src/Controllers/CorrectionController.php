<?php
namespace App\Controllers;
use App\Database;

class CorrectionController {
  public function store(): void {
    if ($_SERVER['REQUEST_METHOD']!=='POST'){ http_response_code(405); exit; }
    $db=Database::pdo();
    $inc=(int)($_POST['incident_id']??0);
    $field=$_POST['field']??'';
    $val=$_POST['new_value']??null;
    $lat=$_POST['new_lat']??null; $lng=$_POST['new_lng']??null;
    $user=$_SESSION['user_id']??null;
    if($inc && $field){
      $st=$db->prepare("INSERT INTO corrections (incident_id,user_id,field,new_value,new_lat,new_lng) VALUES (?,?,?,?,?,?)");
      $st->execute([$inc,$user,$field,$val,$lat,$lng]);
    }
    header('Location: /incidencias/public/incidents'); exit;
  }
}

