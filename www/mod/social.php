<?php
session_start();

if (file_exists('../adm/mod/conn/db_conn.php')) {
    require_once($prefix.'../adm/mod/conn/db_conn.php');
    require_once($prefix.'../adm/mod/debug.php');
}else{
    require_once($prefix.'/adm/mod/conn/db_conn.php');
    require_once($prefix.'/adm/mod/debug.php');
}
debug_to_console( 'dfd '.($_SESSION['token']));
//debug_to_console( 'dfd2 '.($_SESSION['jlogin']));

if(!empty($_SESSION['jlogin']['is_auth'])){
    $net=$_SESSION['jlogin']['profile']['logoprov'];
    include_once('class/socialclass.php');
    $aunt = new Social('comuser',$db);
    $aunt->createId($net);
    $outssesion = $aunt->outName($_SESSION['jlogin']['profile']);


} else {

    if (isset($_GET['code'])) {
       $net=$_SERVER['REQUEST_URI']; $net2='';
       if(stristr($net,'vk_aunth')) {$net2='vk';}
        elseif(stristr($net,'google_aunth')) {$net2='go';}

        debug_to_console( 'dfdcode '.($_SESSION['token']));
        include_once('class/socialclass.php');
        $ul = $_GET['code'];
        $aunt = new Social('comuser', $db);
        $aunt->createId($net2);
        $aunt->tokenGet($ul);
        $outssesion=$aunt->outName($_SESSION['jlogin']['profile']);
       // echo json_encode($outssesion);
        // debug_to_console( ($ul));
        // debug_to_console($ul->first_name);
        // $outssesion=$aunt->outName($_SESSION['ulogin']['profile']);
        // echo json_encode($outssesion);
    } else {
        //  require_once($prefix.'/adm/mod/conn/db_conn.php');
        //   require_once($prefix.'/adm/mod/debug.php');
        include_once('class/socialclass.php');

        $aunt = new Social('comuser', $db);
        $aunt->createId('vk');
      //  if (!empty($_SESSION['ulogin']['is_auth'])) {
     //       $outssesion = $aunt->outName($_SESSION['ulogin']['profile']);
      //  } else {
            $outssesion = $aunt->outLogSoc(1);
     //   }
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