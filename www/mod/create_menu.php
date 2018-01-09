<?php
$prefix='';//'http://'.$_SERVER["HTTP_HOST"];
require_once($prefix.'/adm/mod/conn/db_conn.php');
require_once($prefix.'/adm/mod/debug.php');

include('class/createmenu.php');
include('class/makemenu.php');

//debug_to_console('http://'.$_SERVER["DOCUMENT_ROOT"].'/');
$menu = new makeMenu('mainmenu',$db);
$menumodern=$menu->makemodernMenu();

$cur_url=$_SERVER['REQUEST_URI'];
$cur_url=explode('/',$cur_url);
$page=new makeMenu('rasdel',$db);
$masskod=$page->getKodes($cur_url,'mainmenu');
$needkm=$masskod[0]; $needkr=$masskod[1];
if($needkr==0){$itogname=$needkm.'_0_0';} else{$itogname=$needkr.'_'.$needkm.'_0';}

$where=' WHERE kodmenu='.$needkm. ' and kodrasdel='. 0 . ' and vyvod=1 ';
//debug_to_console($where);
$page->initRasdMen(0,$needkm,$itogname);
$page->massivRasdel($where,1);
//debug_to_console($menu->mainmenu);
$menuhoriz=$page->makeHorMenu();
//$page->initRasdMen($needkr,$needkm,$itogname);
$where=' WHERE kodmenu='.$needkm. ' and kodrasdel='.$needkr. ' and vyvod=1 ';
$page->initRasdMenOnly($needkr,$needkm, $itogname);
$massart=$page->currentArticle('news',$where,$itogname);
//($massart);
//echo json_encode(array($menuhoriz,$massart));

//debug_to_console($menuhoriz);

?>