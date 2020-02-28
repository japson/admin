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
	$temp=$windrecord->checkfields($data);
		if (strlen($temp)==0){
			//debug_to_console($_POST['data']);
			$windrecord->nameImgTbl();
			$windrecord->createEditableFields($tablic,$record, $tbl_select);
			$mass_actions=array('Сохранить'=>'saveRecord','Удалить'=>'delRecord');
			if ($tablic=='punkt') {$mass_actions['Ссылка']='linkRecord';}
			$windrecord->createButton($mass_actions);
			$buttons=$windrecord->buttSortReturn();
			
			//debug_to_console($windrecord->tbl_img);
			$out=$windrecord->createOutFields($tablic,$record,$buttons);
			//echo $out;
			$mass[0]['atribut']=1;$mass[0]['text']=$out;
		}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с полями2.";}
	
	}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с таблами.";}
}

echo json_encode($mass);	
?>