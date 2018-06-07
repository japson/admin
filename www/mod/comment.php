<?php
session_start();

//echo $itogname;
//if($_POST['updatcom']==0){$updcom='ertre0';}else{$updcom=$_POST['updatcom'];}
if(!isset($_POST['updatcom']) && !isset($_POST['listcom'])){$switch=0;$tempload=0;}
if($_POST['updatcom']){$switch=1;$tempload=1;}
if($_POST['listcom']){$switch=2;$tempload=1;}

$prefix='';


if (file_exists('../adm/mod/conn/db_conn.php')) {
    require_once($prefix.'../adm/mod/conn/db_conn.php');
    require_once($prefix.'../adm/mod/debug.php');
}else{
    require_once($prefix.'/adm/mod/conn/db_conn.php');
    require_once($prefix.'/adm/mod/debug.php');
}

include('class/commclass.php');
include('class/commentoutput.php');

$comm = new OutputComment('comment',$db);
switch ($switch){
    case '0': $outcomment=$comm->loadPageCom();
        break;
    case '1': $outcomment=$comm->inputCom();
        if($outcomment[0]) {output($comm,$outcomment);}
    else{echo json_encode($outcomment);}
        break;
    case '2': $outcomment=$comm->outCom();
        if($outcomment[0]){ $html=$comm->buildList($comm->massitog);
            echo json_encode(array(1,$html[0],$html[1]));}
        else{
        echo json_encode($outcomment);}
        break;

}
function output($comm,$outcomment){
    if(!$comm->makeListComm()) {$outcomment[0]=array(0,'no pologeno main');};
    if($outcomment[0]){ $html=$comm->buildList($comm->massitog);
        echo json_encode(array(1,$html[0],$html[1]));}
    else{
        echo json_encode($outcomment);}
}


?>