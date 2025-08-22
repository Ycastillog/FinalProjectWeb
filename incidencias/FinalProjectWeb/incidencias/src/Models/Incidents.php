<?php
namespace App\Models;
use PDO;

class Incidents extends BaseModel {

  public function provinces(): array {
    return $this->db->query("SELECT id,name FROM provinces ORDER BY name")->fetchAll();
  }
  public function types(): array {
    return $this->db->query("SELECT id,name,icon FROM incident_types ORDER BY name")->fetchAll();
  }

  public function allWithFilters(array $f): array {
    $where = ["i.status IN ('approved','pending','rejected')"];
    $p = [];
    if (!empty($f['q'])) { $where[]="(i.title LIKE ? OR i.description LIKE ?)"; $p[]='%'.$f['q'].'%'; $p[]='%'.$f['q'].'%'; }
    if (!empty($f['province_id'])) { $where[]="i.province_id=?"; $p[]=(int)$f['province_id']; }
    if (!empty($f['type_id'])) { $where[]="EXISTS (SELECT 1 FROM incident_type_pivot itp WHERE itp.incident_id=i.id AND itp.type_id=?)"; $p[]=(int)$f['type_id']; }
    if (!empty($f['from'])) { $where[]="i.occurred_at>=?"; $p[]=$f['from'].' 00:00:00'; }
    if (!empty($f['to']))   { $where[]="i.occurred_at<=?"; $p[]=$f['to'].' 23:59:59'; }

    $sql = "SELECT i.*, u.username, p.name AS province_name
            FROM incidents i
            LEFT JOIN users u ON u.id=i.user_id
            LEFT JOIN provinces p ON p.id=i.province_id
            ".($where ? "WHERE ".implode(" AND ", $where) : "")."
            ORDER BY i.occurred_at DESC LIMIT 200";
    $st=$this->db->prepare($sql); $st->execute($p);
    $rows=$st->fetchAll();

    $ids = array_column($rows,'id'); if(!$ids) return $rows;
    $in = implode(',', array_fill(0,count($ids),'?'));
    $st=$this->db->prepare("SELECT itp.incident_id,t.id as type_id,t.name,t.icon
                            FROM incident_type_pivot itp JOIN incident_types t ON t.id=itp.type_id
                            WHERE itp.incident_id IN ($in)");
    $st->execute($ids);
    $by=[]; foreach($st->fetchAll() as $r){ $by[$r['incident_id']][]=['id'=>$r['type_id'],'name'=>$r['name'],'icon'=>$r['icon']]; }
    foreach($rows as &$r){ $r['types'] = $by[$r['id']] ?? []; }
    return $rows;
  }

  public function create(array $d, ?int $userId): int {
    $st=$this->db->prepare("INSERT INTO incidents
      (title,description,occurred_at,province_id,municipality_id,barrio_id,lat,lng,dead,injured,loss_rd,social_link,photo,status,user_id)
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,'pending',?)");
    $st->execute([
      $d['title'],$d['description'],$d['occurred_at'],
      $d['province_id']?:null,$d['municipality_id']?:null,$d['barrio_id']?:null,
      $d['lat']?:null,$d['lng']?:null,$d['dead']?:null,$d['injured']?:null,$d['loss_rd']?:null,
      $d['social_link']?:null,$d['photo']??null,$userId
    ]);
    $id=(int)$this->db->lastInsertId();

    if (!empty($d['type_ids']) && is_array($d['type_ids'])) {
      $ins=$this->db->prepare("INSERT IGNORE INTO incident_type_pivot (incident_id,type_id) VALUES (?,?)");
      foreach($d['type_ids'] as $tid){ $ins->execute([$id,(int)$tid]); }
    }
    return $id;
  }

  public function recentApproved24h(): array {
    $st=$this->db->prepare("SELECT i.id,i.title,i.lat,i.lng,i.dead,i.injured,i.loss_rd,i.occurred_at
                            FROM incidents i
                            WHERE i.status='approved' AND i.lat IS NOT NULL AND i.lng IS NOT NULL
                              AND i.occurred_at >= (NOW() - INTERVAL 24 HOUR)");
    $st->execute(); $rows=$st->fetchAll();
    if(!$rows) return [];
    $ids=array_column($rows,'id'); $in=implode(',', array_fill(0,count($ids),'?'));
    $st=$this->db->prepare("SELECT itp.incident_id,t.name,t.icon
                            FROM incident_type_pivot itp JOIN incident_types t ON t.id=itp.type_id
                            WHERE itp.incident_id IN ($in)");
    $st->execute($ids); $by=[];
    foreach($st->fetchAll() as $r){ $by[$r['incident_id']][]=$r; }
    foreach($rows as &$r){ $r['types']=$by[$r['id']]??[]; }
    return $rows;
  }

  public function pending(): array {
    return $this->db->query("SELECT * FROM incidents WHERE status='pending' ORDER BY occurred_at DESC")->fetchAll();
  }
  public function approve(int $id): void { $this->db->prepare("UPDATE incidents SET status='approved' WHERE id=?")->execute([$id]); }
  public function reject(int $id): void   { $this->db->prepare("UPDATE incidents SET status='rejected' WHERE id=?")->execute([$id]); }
  public function merge(int $winner,int $loser): void { $this->db->prepare("UPDATE incidents SET merged_into_id=? WHERE id=?")->execute([$winner,$loser]); }
}
