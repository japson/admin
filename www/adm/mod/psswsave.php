<?
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();

if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1) {
    $data = json_decode($_POST['data']);
    $record=str_replace('nom','',$_POST['record']);
    include('class/var_alt.php');
    $key=array_search($data->table, $massTablAlias);
    If(checkAdmin($key,$record)){}else{$key='';$mass[0]['atribut']=0;$mass[0]['text']="Нет прав.";}
        if(strlen($key)>0) {$tablic=$key;
            $mdPassword = md5($data->pssw);
           // debug_to_console($mdPassword);
            $sql=' UPDATE '.$tablic.' SET pssw="'.$mdPassword.'", aunt="" WHERE kod='.$record;
           // debug_to_console($sql);
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $mass[0]['atribut']=1;$mass[0]['text']='ok';

            $korendir=$_SERVER['HTTP_HOST'];
            $korendir=str_replace('www.','',$korendir);

           // setcookie("auth_key", "",time() - (60 * 60 * 24 * 1), "/",$korendir);
        }else{$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с таблами.";}

}else{$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с коннектом.";}
echo json_encode($mass);


function checkAdmin($tbl, $user){
    global $db;
    $sql='Select * FROM '.$tbl. ' WHERE kod='.$user ;
    //debug_to_console($sql);
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $dbuser=$row['login'];
    $temp=1;
    if($row['locker']==1){
        $temp=0;
        if($_COOKIE['user']==$dbuser) {$temp=1;}
    }
    return $temp;
}

?>
