<?php
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();

if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1) {
    $data=json_decode($_POST['param']);
    include('class/var_alt.php');
    if(gettype($data)=='object') {
        if(strlen($data->table)){$tabl=$data->table;}
        if(strlen($data->kodmenu)){$kodmenu=$data->kodmenu;}
        if(strlen($data->kodrasdel)){$kodmenu=$data->kodrasdel;}
    }
    $key=array_search($tabl, $massTablAlias);
        if(strlen($key)>0) {$tablic = $key;
         $put=$massElements[$tablic];
            if(strlen($put)>0) { //ветка с файлами
                $prefix=$massPrefix[$tablic]; //prefix table
                include('class/dir_watch.php');
                if(strlen($prefix)>0){$put=$prefix.$put;}
               // $put.='вапавю.ап/';
                $newdir=new DirWatch($put,$tablic);
                if($newdir->nodir) {
                    $temp=$newdir->scandir('');
                } else{echo json_encode('no dir');}
            } else{} // ветка без файлов просто каталог
        }


    echo json_encode($temp);
}

?>