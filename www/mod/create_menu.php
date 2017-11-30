<?php
$prefix='';//'http://'.$_SERVER["HTTP_HOST"];
require_once($prefix.'/adm/mod/conn/db_conn.php');
require_once($prefix.'/adm/mod/debug.php');

include('class/createmenu.php');
include('class/makemenu.php');

$menu = new makeMenu('mainmenu',$db);
$menumodern=$menu->makemodernMenu();


?>