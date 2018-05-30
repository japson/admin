<?php
session_start();
if (isset($_POST['ulet'])) {
    require_once($prefix.'../adm/mod/conn/db_conn.php');
    require_once($prefix.'../adm/mod/debug.php');
    include('class/socialclass.php');

    $ul=json_decode($_POST['ulet']);
   // if()
    $aunt = new Social('comuser',$db);
    $aunt->tokenGet($ul);
   // debug_to_console( ($ul));
   // debug_to_console($ul->first_name);
    $outssesion=$aunt->outName($_SESSION['ulogin']['profile']);
    echo json_encode($outssesion);
}
else{
    require_once($prefix.'/adm/mod/conn/db_conn.php');
    require_once($prefix.'/adm/mod/debug.php');
    include('class/socialclass.php');

    $aunt = new Social('comuser', $db);
    if (!empty($_SESSION['ulogin']['is_auth'])) {
        $outssesion = $aunt->outName($_SESSION['ulogin']['profile']);
    } else {
        $outssesion = $aunt->outLogSoc(1);
    }
}

/*if (!isset($_POST['token'])) { //если нет токена проверить id сессии
   // debug_to_console( $_SESSION['loginza']['is_auth']);
    $aunt = new Social('comuser',$db);
    if(!empty($_SESSION['loginza']['is_auth'])) {

      //  debug_to_console($_SESSION['loginza']['profile']);
        $outssesion=$aunt->outName($_SESSION['loginza']['profile']);

    } else{
        $outssesion = $aunt->outLogSoc(1);}
}Else { // отправка токена заново аунтификация
    $aunt = new Social('comuser',$db);
    $aunt->tokenGet($_POST['token']);
    $outssesion=$aunt->outName($_SESSION['loginza']['profile']);

}*/


?>