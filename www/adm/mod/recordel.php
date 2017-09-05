<?
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();

if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
	$param=json_decode($_POST['data']);
	//$record=str_replace('nom','',$_POST['record']);
	
	include('class/var_alt.php');
	if(gettype($param)=='object') {
        if(strlen($param->table)){$tabl=$param->table;}
        if(strlen($param->kodmenu)){$kodmenu=$param->kodmenu;}
        if(strlen($param->kodrasdel)){$kodrasdel=$param->kodrasdel;}
        if(strlen($param->nomrec)){$record=str_replace('nom','',$param->nomrec);}
	}
	$key=array_search($tabl, $massTablAlias);
		if(strlen($key)>0) {$tablic=$key;
		include('class/init_table.php');
		include('class/menu_table.php');
		include('class/record_edit.php');
		include('class/variables.php');
		$windrecord= new RecordEdit($tablic,$db);
		$windrecord->kodrasdel=$kodrasdel;
		$windrecord->kodmenu=$kodmenu;
            $temp=$windrecord->checkDel($record,'kod');
		//debug_to_console($temp);
			if (strlen($temp)==0) {
			$mass[0]['atribut']=1;$mass[0]['text']='';	
			}else{$mass[0]['atribut']=0;$mass[0]['text']=$temp;} 
		}else{$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с таблами.";}

}
echo json_encode($mass);
?>