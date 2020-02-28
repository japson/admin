<?php
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck(); $tmp=0;
$tabl=''; $kodrasdel=0;$kodmenu=0;
if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
    $data=json_decode($_POST['data']);
    if(gettype($data)=='object') {
        if(strlen($data->table)){$tabl=$data->table;}
        if(strlen($data->kodmenu)){$kodmenu=$data->kodmenu;}
        if(strlen($data->kodrasdel)){$kodrasdel=$data->kodrasdel;}
        $direct=$data->direct;
    }
    include('class/var_alt.php');
    $key=array_search($tabl, $massTablAlias);
    if(strlen($key)>0) {$tablic=$key;
        include('class/variables.php');
        include('class/init_table.php');
        include('class/set_get_file.php');
        $file= new setGetFile($tablic,$db);
        $prefix = '../../';
        $name = $prefix . 'catalog/punkts/punkts.txt';
        if($direct==0) { //Выгрузить
            $where = ' WHERE kodmenu=' . $kodmenu . ' AND kodrasdel=' . $kodrasdel . " ORDER BY  sort";
            $field = Array('artist', 'title', 'put', 'length', 'side', 'sort');
            $json = $file->sendAllData($field, $where);
            $file->createfile($name, $json);
            $tmp=0;
        }else{
            $array = $file->openfile($name);
            if(count($array)){
                $tmp=$file->getAllData($array,Array('kodmenu'=>$kodmenu,'kodrasdel'=>$kodrasdel));
            }

        }
    }
    //echo json_encode($data->table);
    echo json_encode(array($tmp,0));
}


?>