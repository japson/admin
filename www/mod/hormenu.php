<?php
$prefix='';//'http://'.$_SERVER["HTTP_HOST"];
require_once($prefix.'../adm/mod/conn/db_conn.php');
require_once($prefix.'../adm/mod/debug.php');

$what=$_POST['what'];
$kod=$_POST['key']; $tbl=$_POST['tbl']; $kodmenu=$_POST['km']; $kodrasdel=$_POST['kr'];

include('class/createmenu.php');
include('class/makemenu.php');

    if($what=='hm') {
        $needtbl='rasdel';
        $needkm=$kod; $needkr=0;
        $menu = new makeMenu($needtbl,$db);
        $where=' WHERE kodmenu='.$needkm. ' and kodrasdel='.$needkr. ' and vyvod=1 ';
        $menu->initRasdMen($needkr,$needkm);
        $menu->massivRasdel($where,1);
        echo json_encode($menu->mainmenu);
        //$menuhoriz=$menu->makeHorMenu();

    }


?>