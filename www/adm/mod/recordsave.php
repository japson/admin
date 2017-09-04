<?
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();

if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
	$data=json_decode($_POST['data']);
	$tabl=$_POST['tbl'];
	$record=$_POST['record'];
	include('class/var_alt.php');
	$key=array_search($tabl, $massTablAlias);
		if(strlen($key)>0) {$tablic=$key;
		include('class/init_table.php');
		include('class/menu_table.php');
		include('class/record_edit.php');
		include('class/variables.php');
		$windrecord= new RecordEdit($tablic,$db);
		$temp=$windrecord->checkfields($windrecord->createfields($data));
			if (strlen($temp)==0){
				$windrecord->nameImgTbl();
				$windrecord->saveOutFields($data,$record);
				$rec=str_replace("nom", "", $record);
				$windrecord->outmasskey($userpunkt,$massAssoc);
				$windrecord->countrec(" WHERE kod=".(int)$rec);
				//debug_to_console($windrecord->massvalue());
				$windrecord->createButton($mass_actions);
				$out=$windrecord->recordOut($massTypField, $tbl_select, '?'.$tabl.'=');
				//debug_to_console($out);
				$mass[0]['atribut']=1;$mass[0]['text']=$out;
			} else{$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с полями.";}
		}else{$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с таблами.";}

}
echo json_encode($mass);
?>