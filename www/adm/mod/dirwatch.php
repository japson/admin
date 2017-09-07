<?php
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();

if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1) {
    $data=json_decode($_POST['param']);
    $curput=json_decode($_POST['put']);
    include('class/var_alt.php');
    if(gettype($data)=='object') {
        if(strlen($data->table)){$tabl=$data->table;}
        if(strlen($data->kodmenu)){$kodmenu=$data->kodmenu;}
        if(strlen($data->kodrasdel)){$kodmenu=$data->kodrasdel;}
    }
    if(gettype($curput)=='object') {if(strlen($curput->put)){$putnext=$curput->put;$putback=$curput->backput;}else{$putnext='';}}
    $key=array_search($tabl, $massTablAlias);
        if(strlen($key)>0) {$tablic = $key;
         $put=$massElements[$tablic];
            if(strlen($put)>0) { //ветка с файлами
                $prefix=$massPrefix[$tablic]; //prefix table
                if(strlen($prefix)>0){$put=$prefix.$put;}
                if(strlen($putnext)>0){$put=$putnext;}
                include('class/dir_watch.php');
                // $put.='русск/';
                 $newdir=new DirWatch($put,$tablic);
                if($newdir->nodir) {
                    if(strlen($putback)>0){$newdir->comeback($putback);}
                    $pust=$newdir->scandir('');
                    $temp=$newdir->outDir();
                    //debug_to_console($newdir->temper);
                } else{echo json_encode('no dir');}
            } else{} // ветка без файлов просто каталог
        }


    echo json_encode($temp);
}

?>