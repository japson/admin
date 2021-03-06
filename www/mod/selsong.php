<?php
$prefix='';//'http://'.$_SERVER["HTTP_HOST"];
require_once($prefix.'../adm/mod/conn/db_conn.php');
require_once($prefix.'../adm/mod/debug.php');

$id=$_POST['id'];
$typ=$_POST['typ'];

if(intval($id) && $typ=='one') {$temp=selSong($id);}
if($typ=='all') {$temp=selSongAll($id);}
//debug_to_console($temp);

echo json_encode($temp);
//---------------------
function selSong($id){
    global $db; $tbl='punkt'; $where=' WHERE kod=?';
    $sql = "SELECT * FROM ".$tbl." ".$where."  ORDER by sort";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($id));
    if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
        $temp=array('id'=>$id,'song'=>$sms[0]['title'].' - '. $sms[0]['artist'],'put'=>htmlspecialchars_decode($sms[0]['put']),'times'=>$sms[0]['length'] );
    } else {$temp=array('id'=>0);}
    return $temp;
}

function selSongAll($id){
    $menras=explode('_',$id);$temp=array();
    global $db; $tbl='punkt'; $where=' WHERE kodmenu=? AND kodrasdel=?';
    $sql = "SELECT * FROM ".$tbl." ".$where."  ORDER by sort";
    $stmt = $db->prepare($sql);
    $stmt->execute($menras);
    if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
        foreach($sms as $value) {
            $temp[] = array('id' => $value['kod'], 'side' => $value['side'], 'song' => $value['title'] . ' - ' . $value['artist'], 'put' => htmlspecialchars_decode($value['put']),'times'=>$value['length']);
        }
    }
    if(!count($temp)){$temp=array('id'=>0);}
    return $temp;
}
?>