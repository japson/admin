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
if($_SESSION['jlogin']['is_auth'] == 1){
    $name='Интересная подборка похоже';
    if ($_POST['txt']){$name=$_POST['txt'];}
    $take=new makelist('usersong',$db);
    $take->checkuserList();
    $tmp=$take->checkCurrentPlayList('userlist');
    $take->saveUserPlayList('userlist',$tmp,$name);

    echo json_encode(array(1,'Кассета в каталоге Плейлистов'));
   // $result=$take->saveList($_POST['sides'], $_POST['nomsongs'] );

} else {echo json_encode(array(0,'Вы не авторизированны.'));}
?>