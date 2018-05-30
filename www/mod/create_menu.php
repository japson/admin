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
//debug_to_console($_SERVER["QUERY_STRING"] );
$cur_url=explode('/',$cur_url);
$page=new makeMenu('rasdel',$db);
$masskod=$page->getKodes($cur_url,'mainmenu');
debug_to_console($masskod);
$needkm=$masskod[0]; $needkr=$masskod[1]; $needart=$masskod[2];
//if($needkr==0){$itogname=$needkm.'_0_0';} else{$itogname=$needkr.'_'.$needkm.'_0';}

$itogname=$needkm.'_0_0'; // код для среднего меню
$where=' WHERE kodmenu='.$needkm. ' and kodrasdel='. 0 . ' and vyvod=1 ';
debug_to_console($itogname);
$page->initRasdMen(0,$needkm,$itogname);
$pictsearch=1;
//debug_to_console($needkm);
if($needkm==0) {$page->renameTabl('mainmenu');$pictsearch=0;}
$page->massivRasdel($where,$pictsearch);
//debug_to_console($page->allrasdel);
$menuhoriz=$page->makeHorMenu();
//$page->initRasdMen($needkr,$needkm,$itogname);

// код для статьи
//debug_to_console('art '.$needart);
if ($needart){
    $itogname=$needart.'_'.$needkm.'_'.$needkr;
    $where=' WHERE kod='.$needart. ' and vyvod=1 ';}
    else{$itogname=$needkr.'_'.$needkm.'_0';
        $where=' WHERE kodmenu='.$needkm. ' and kodrasdel='.$needkr. ' and vyvod=1 ';}
        //$itogname=$needkr.'_'.$needkm.'_0';

//debug_to_console($page->cofmen);
$page->initRasdMenOnly($needkr,$needkm, $itogname);
$page->opengraph['url']='http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$page->kodarticle=$needart;
$massart=$page->currentArticle('news',$where,$itogname);
if($needart) {
    $nextart = ' name=' . $page->nextart[0] . ' dathref=' . $page->nextart[1] . ' ';
    $prevart = ' name=' . $page->prevart[0] . ' dathref=' . $page->prevart[1] . ' ';
    $listart = ' name=' . $page->listart[0] . ' dathref=' . $page->listart[1] . ' ';
}
$page->opengraph=$menu->checkOpenGraph($massart[1]);
//($massart);
//echo json_encode(array($menuhoriz,$massart));

//debug_to_console($menuhoriz);

?>