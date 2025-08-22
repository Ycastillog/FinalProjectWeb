<?php
namespace App\Controllers;
use App\Database;

class CommentController {
  public function store(): void {
    if ($_SERVER['REQUEST_METHOD']!=='POST'){ http_response_code(405); exit; }
    $db=Database::pdo();
    $inc=(int)($_POST['incident_id']??0);
    $body=trim($_POST['body']??'');
    $user=$_SESSION['user_id']??null;
    if($inc && $body!==''){
      $st=$db->prepare("INSERT INTO comments (incident_id,user_id,body) VALUES (?,?,?)");
      $st->execute([$inc,$user,$body]);
    }
    header('Location: /incidencias/public/incidents'); exit;
  }
}

