<?php
session_start();

if (file_exists('../adm/mod/conn/db_conn.php')) {
    require_once($prefix.'../adm/mod/conn/db_conn.php');
    require_once($prefix.'../adm/mod/debug.php');
}else{
    require_once($prefix.'/adm/mod/conn/db_conn.php');
    require_once($prefix.'/adm/mod/debug.php');
}
$vybor=$_POST['id'];

if($vybor==1){ $outssesion='';
    if(!empty($_SESSION['jlogin']['is_auth'])) {
        include_once('class/socialclass.php');
        $net = $_SESSION['jlogin']['profile']['logoprov'];
        $aunt = new Social('comuser', $db);
        $aunt->createId($net);
        if(strlen($_POST['typ'])){
        $outssesion=$aunt->makeNick($_POST['typ']);
        }
    }
if(strlen($outssesion)) {echo json_encode(array(1,$outssesion));} else{echo json_encode(array(0,'problem'));}
}

if($vybor==2){
    if(strlen($_POST['jens'])){ $tmp=array(0,'problem');
        $list=str_replace('cassette','',$_POST['jens']);
        include_once('class/makelist.php');
        $aunt = new makelist('userlist', $db);
        $tmp=$aunt->genList($list);
        if(!count($tmp)) {$tmp=array(0,'problem');}
    }
    echo json_encode($tmp);
}
if($vybor==3){
    if(strlen($_POST['koder'])){ $tmp=array(0,'problem');
        $list=str_replace('list','',$_POST['koder']);
        include_once('class/makelist.php');
        $take=new makelist('userlist',$db);
        $take->checkSCassete($list,'kod');
        $take->makeList();
        echo json_encode(array($take->mainsong,$take->mainsides,$take->maintitles,$take->songs,$take->maintimes));
    }

}

?>