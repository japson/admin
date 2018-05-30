<?php
$prefix='';//'http://'.$_SERVER["HTTP_HOST"];
require_once($prefix.'../adm/mod/conn/db_conn.php');
require_once($prefix.'../adm/mod/debug.php');

$what=$_POST['what'];
$href=$_POST['href'];
$findme    = 'http:';
$pos2 = stripos($href, $findme);
if($pos2=== false) $href='http://'.$_SERVER['SERVER_NAME'].$href;
$kod=$_POST['key']; $tbl=$_POST['tbl']; $kodmenu=$_POST['km']; $kodrasdel=$_POST['kr'];
$itogname=$kod.'_'.$kodmenu.'_'.$kodrasdel;
include('class/createmenu.php');
include('class/makemenu.php');

    if($what=='hm') {
        $needkm=$kod; $needkr=0;
        if($needkm==0){$needtbl='mainmenu';}
        else{$needtbl='rasdel';}
        $menu = new makeMenu($needtbl,$db);
        $where=' WHERE kodmenu='.$needkm. ' and kodrasdel='.$needkr. ' and vyvod=1 ';
        $menu->initRasdMen($needkr,$needkm,$itogname);
        $menu->massivRasdel($where,1);
        //echo json_encode($menu->mainmenu);
        $menuhoriz=$menu->makeHorMenu();
        $menu->opengraph['url']=$href;
        $massart=$menu->currentArticle('news',$where,$itogname);
       // debug_to_console($menu->opengraph);
        $massart[1]=$menu->checkOpenGraph($massart[1]);
        echo json_encode(array($menuhoriz,$massart));
    }

    if($what=='hmr') { //только меню
        $needkm=$kod; $needkr=0;
        if($needkm==0){$needtbl='mainmenu';}
        else{$needtbl='rasdel';}
        $menu = new makeMenu($needtbl,$db);
        $where=' WHERE kodmenu='.$needkm. ' and kodrasdel='.$needkr. ' and vyvod=1 ';
        $menu->initRasdMen($needkr,$needkm,$itogname);
        $menu->massivRasdel($where,1);
        //echo json_encode($menu->mainmenu);
        $menuhoriz=$menu->makeHorMenu();
        $massart='';

        echo json_encode(array($menuhoriz,$massart));

    }
    if($what=='ma') { // статьи меню
        $needtbl='rasdel';
        $needkm=$kod; $needkr=0;
        $menu = new makeMenu($needtbl,$db);
        $where=' WHERE kodmenu='.$needkm. ' and kodrasdel='.$needkr. ' and vyvod=1 ';
        $menu->initRasdMenOnly($needkr,$needkm, $itogname);
        $menu->opengraph['url']=$href;
        //debug_to_console($menu->opengraph);
        $massart=$menu->currentArticle('news',$where,$itogname);
        $massart[1]=$menu->checkOpenGraph($massart[1]);
        echo json_encode(array('',$massart));
    }

    if($what=='ra') { // статьи раздела переход из верхнего меню
        $needtbl='rasdel';
        $needkm=$kodmenu; $needkr=$kod;
        $menu = new makeMenu($needtbl,$db);
        $where=' WHERE kodmenu='.$needkm. ' and kodrasdel='.$needkr. ' and vyvod=1 ';
       // debug_to_console($where);
        $menu->initRasdMenOnly($needkr,$needkm, $itogname);
       // $menu->initRasdMen($needkr,$needkm,$itogname);
      //  $menu->massivRasdel($where,0);
        $menu->opengraph['url']=$href;
        $massart=$menu->currentArticle('news',$where,$itogname);
        $massart[1]=$menu->checkOpenGraph($massart[1]);
      //  debug_to_console($massart);
        echo json_encode(array('',$massart));
    }

    if($what=='oa') { // статьи раздела зайти в статью
        $needtbl='news';
        $needkm=$kodmenu; $needkr=$kodrasdel;
        $menu = new makeMenu($needtbl,$db);
        $where=' WHERE kod='.$kod. ' and vyvod=1 ';
        $menu->initRasdMenOnly($needkr,$needkm, $itogname);
        $menu->opengraph['url']=$href;

        $menu->kodarticle=$kod;
        $massart=$menu->currentArticle('news',$where,$itogname);
        $massart[1]=$menu->checkOpenGraph($massart[1]);
        echo json_encode(array('',$massart,$menu->prevart,$menu->nextart,$menu->listart));
    }
?>