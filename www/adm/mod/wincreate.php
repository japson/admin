<?
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();
//echo ('');
if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
	$mass_param=array(); $tabl=''; $$alias='';
	$param=$_POST['param'];
	$data=json_decode($_POST['tbl']);
	//debug_to_console($data);
		if(gettype($data)=='object') {
			if(strlen($data->table)){$tabl=$data->table;}
			if(strlen($data->alias)){$alias=$data->alias;}
		}
	include('class/var_alt.php');
	$key=array_search($tabl, $massTablAlias);
	//debug_to_console($key);
		if(strlen($key)>0) {$tablic=$key;
		include('class/variables.php');
		include('class/init_table.php');
		include('class/window_input.php');
		$windinput= new WindowInput($tablic,$db);
		$windinput->outmasskey($inputpunkt,$massAssoc);
		$windinput->aliasform=$alias;
		$out=$windinput->createFields($param);
		$windinput->inputAction('saveNew');
		$out.=$windinput->outAction();
		//debug_to_console($out);
		$mass[0]['atribut']=1;$mass[0]['text']=$out;
		//debug_to_console($windinput->masskey());
		}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с таблицей.";}
}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с правами.";}

echo json_encode($mass);	
?>