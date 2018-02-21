<?php
$prefix='';//'http://'.$_SERVER["HTTP_HOST"];
require_once($prefix.'../adm/mod/conn/db_conn.php');
require_once($prefix.'../adm/mod/debug.php');

$id=$_POST['id'];
$typ=$_POST['typ'];

if(intval($id)) {$temp=selSong($id);}

//debug_to_console($temp);

echo json_encode($temp);
//---------------------
function selSong($id){
    global $db; $tbl='punkt'; $where=' WHERE kod=?';
    $sql = "SELECT * FROM ".$tbl." ".$where."  ORDER by sort";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($id));
    if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
        $temp=array('id'=>$id,'song'=>$sms[0]['artist'].' - '. $sms[0]['title'],'put'=>$sms[0]['put'] );
    } else {$temp=array('id'=>0);}
    return $temp;
}
?>