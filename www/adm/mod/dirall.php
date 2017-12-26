<?php
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();

if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
    $data=($_POST['param']);
    $id=($_POST['id']);
    include('class/var_alt.php');
   // debug_to_console($data['table']);
    $key=array_search($data['table'], $massTablAlias);
    if(strlen($key)>0) {
        $tablic = $key;
       // debug_to_console($tablic);
        include('class/dir_all.php');
        $windrecord = new dirPunkt($tablic, $db);
        $massmenu = $windrecord->selectName('mainmenu', 'kodmenu', '');// menu
        $massrasd = $windrecord->selectName('rasdel', 'kodrasdel', '');//rasdel
       // $mass2=$windrecord->buildPut($massmenu,$massrasd);//all dir in put
        $windrecord->buildPutMini($massmenu,$massrasd);
        $windrecord->actselect='redirSelect(event);';
        $windrecord->makeUrl();
        $link=$windrecord->testLink(str_replace('nom','',$id));
        //debug_to_console($link);
        $link='<div class="oldput">Линк: '.$link.'</div>';
        $mass=$windrecord->allmakedir;
        $mass['outbutton']=$link.$windrecord->outAction('redirSave') ;
        $mass['record']=$id.'_'.$data['table'];
    }
    //debug_to_console($massrasd);
    //debug_to_console($mass2);
    $tmp=json_encode($mass);
  //  $tmp2=json_encode($mass2);
    $mass=$tmp; //[up, current]
}
echo ($mass);
?>