<?
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();
//echo ('');
if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
	foreach( $_FILES as $file ){
	$galeryfile = $_FILES['name'];
	}
	if (strlen($galeryfile['name'])==0) {$mass[0]['atribut']=0;$mass[0]['text']="Файл не выбран."; echo json_encode($mass); exit();}
	$tabl='';
	$note=$_POST['note'];
	$file=$_POST['file'];
	$nomer=$_POST['nomer'];
	$nomer=str_replace('nom','',$nomer);
	$master=json_decode($_POST['mass']);
	if(gettype($master)=='object') {
			if(strlen($master->table)){$tabl=$master->table;}
	}
	include('class/var_alt.php');
	$key=array_search($tabl, $massTablAlias);
	//debug_to_console($key);
		if(strlen($key)>0) {$tablic=$key;
		include('class/win_pictinput.php');
		include('class/win_pictsave.php');
		$windinput= new WindowSavePictur($tablic,$db);
		$windinput->imgAlias();
		$windinput->defaultValue();
		$mass_vyvod=$windinput->testPictur($galeryfile,$nomer,$file);
			if ($mass_vyvod['kod']==1) {
				$kolcurrent=$windinput->enginePictur($galeryfile,$nomer,$note);
				$out=$windinput->massPictur($nomer);
				$out.=$windinput->outAction('delOverley');
				//debug_to_console($out);
				$mass[0]['atribut']=1;$mass[0]['text']=$out;$mass[0]['kol']=$kolcurrent;
			}else {$mass[0]['atribut']=0;$mass[0]['text']=$mass_vyvod['text'];}
		}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с таблицей.";}
}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с правами.";}
		
	//debug_to_console(($mass->table));
	//debug_to_console($nomer);
echo json_encode($mass);		
	
	
	
	
	

?>
