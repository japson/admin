<?php
session_start();
if (file_exists('../adm/mod/conn/db_conn.php')) {
    require_once($prefix.'../adm/mod/conn/db_conn.php');
    require_once($prefix.'../adm/mod/debug.php');
}else{
    require_once($prefix.'/adm/mod/conn/db_conn.php');
    require_once($prefix.'/adm/mod/debug.php');
}
require_once('class/makelist.php');
    if ($_POST['sides'] && $_POST['nomsongs'] ){
        $result=0;
        if($_SESSION['jlogin']['is_auth'] == 1){
            $take=new makelist('usersong',$db);
            $result=$take->saveList($_POST['sides'], $_POST['nomsongs'] );

        }
        echo $result;
    }else{
        $take=new makelist('usersong',$db);
        $take->checkSession();
        $take->makeList();
        echo json_encode(array($take->mainsong,$take->mainsides,$take->maintitles,$take->songs,$take->maintimes));
    }




?>