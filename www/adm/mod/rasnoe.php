<?php
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();
$mass=array(0,'no select');

if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1) {
    $data=($_POST['format']);
    if ($data=='selart') {$mass=selart($_POST['id']);}
}
echo json_encode($mass);

function selart($id){
   global $db; $mass=array(0,'no link');
    $sql='SELECT * FROM news Where kod='.$id ;
    $stmt = $db->prepare($sql);
    $stmt->execute();
    if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
        $menu=getname($sms[0]['kodmenu'],'mainmenu');
        $rasdel=getname($sms[0]['kodrasdel'],'rasdel');
        $tmp='<a href="/'.$menu.'/'.$rasdel.'/'.$sms[0]['nameurl'].'" class="pen_list inner" title="'.$sms[0]['name'].'" name="'.$id.'_'.$sms[0]['kodmenu'].'_'.$sms[0]['kodrasdel'].'" onclick="innerGo(event);return false;">'.$sms[0]['name'].'</a> ';
        $mass=array(1,$tmp);
        //<button class="pen_list vpered" title="Следующая статья" name="124_6_25" dathref="/articles/cinema/punk-tvshow" redir=""></button>
    }
    return $mass;
}
function getname($kod,$tbl){
    global $db; $tmp='';
    $sql='SELECT * FROM '.$tbl.' Where kod='.$kod ;
    $stmt = $db->prepare($sql);
    $stmt->execute();
    if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
        $tmp=$sms[0]['nameurl'];
    }
    return $tmp;
}
?>