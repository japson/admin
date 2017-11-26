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
        $massmenu = $windrecord->havePunkt('mainmenu', 'kodmenu', '');// menu
        $massrasd = $windrecord->havePunkt('rasdel', 'kodrasdel', '');//rasdel have punkts
        $mass2=$windrecord->buildPut($massmenu,$massrasd);//all dir in put
        $windrecord->makeUrl();
        $mass=$windrecord->allmakedir;
    }
    //debug_to_console($massmenu);
    $tmp=json_encode($mass);
    $tmp2=json_encode($mass2);
    $mass=$tmp; //[up, current]
}
echo ($mass);
?>