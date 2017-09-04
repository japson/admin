<?
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();

if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
	$zamena=$_POST['recordzam'];
	$tabl=$_POST['tbl'];
	$rasdel=str_replace('nom','',$_POST['rasdel']);
	$record=$_POST['record'];
	include('class/var_alt.php');
	$key=array_search($tabl, $massTablAlias);
		if(strlen($key)>0) {$tablic=$key;
		$tblimg=$picturTbl[$tablic];
		include('class/init_table.php');
		include('class/sort_records.php');
		$sortrecord= new SortRecords($tblimg,$db);
		
		$temp=$sortrecord->checkfields(array('sort'));
		//debug_to_console($temp);
			if (strlen($temp)==0){
				$sortrecord->perevorot($record, $zamena);
				$mass[0]['atribut']=1;$mass[0]['text']='ok';
				}else{$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с полями.";}
		}else{$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с таблами.";}
}
echo json_encode($mass);

?>