<?php
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();

if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
    $data=($_POST['format']);
    include('class/var_alt.php');
    $key=array_search($data, $massTablAlias);
    if(strlen($key)>0) {
        $tablic = $key;
        include('class/dir_punkt.php');
        $windrecord = new dirPunkt($tablic, $db);
        $mass = $windrecord->havePunkt('mainmenu', 'kodmenu', '');
    }
    //debug_to_console($mass);

}
echo json_encode($mass);
?>