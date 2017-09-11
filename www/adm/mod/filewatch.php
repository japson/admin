<?php
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();
if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1) {
    $data=json_decode($_POST['param']);
    $curput=json_decode(($_POST['put']));
   // echo $curput[0];
    include('class/var_alt.php');
    if(gettype($data)=='object') {
        if(strlen($data->table)){$tabl=$data->table;}
        if(strlen($data->kodmenu)){$kodmenu=$data->kodmenu;}
        if(strlen($data->kodrasdel)){$kodmenu=$data->kodrasdel;}
    }
    $key=array_search($tabl, $massTablAlias);
    if(strlen($key)>0) {
        $tablic = $key;
        $put=$massElements[$tablic];
        if(strlen($put)==0) {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с путем для таблицы.";
        echo json_encode($mass); return false;}
        $prefix=$massPrefix[$tablic]; //prefix table
        $put=$prefix.$put;
        include('class/dir_watch.php');
        include('class/file_watch.php');
        $newdir=new fileWatch($put,$tablic);
        if($newdir->nodir) {
            echo json_encode($newdir->checkFile($curput));
        }

    }
    return false;

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
                    $newdir->temper=$curput;
                   // array_push($temp,$_POST['put']);
                    //array_push($temp,$newdir->temper);
                    //debug_to_console($newdir->temper);
                } else{echo json_encode('no dir');}
            } else{} // ветка без файлов просто каталог
        }


    echo json_encode($temp);
}

?>