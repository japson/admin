<?php
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();
$mass=0;
if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
    $data=($_POST['param']);
    $save=($_POST['save']);
    $divider=($_POST['result']);
   // $pole=($_POST['pole']);
    $tbl=$data[1];
    $id=str_replace('nom','',$data[0]);
    include('class/var_alt.php');
   // debug_to_console($data['table']);
    $key=array_search($tbl, $massTablAlias);
    if(strlen($key)>0) {
        $tablic = $key;

        foreach ($save as $key => $value) {
            $keys[] = htmlspecialchars($key) . '=?';
            $znach[] = $value;
        }
        //debug_to_console($znach);
        include('class/dir_all.php');
        $masspol = $pole . '=' . $id;
        $windrecord = new dirPunkt($tablic, $db);
        $vvodata = implode(',', $keys);
        $znach[] = $id;
        //debug_to_console($znach);
        if($divider=='Есть') { $windrecord->saver($vvodata, $znach);}
        if($divider=='Нет') {$znach[0]=0;$windrecord->saver($vvodata, $znach); }
        $mass = 1;

        }

    //$mass=$tmp; //[up, current]
}
echo ($mass);
?>