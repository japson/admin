<?
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();

if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
	$data=json_decode($_POST['data']);

	$wheresort=array();
	$tabl=$_POST['param'];
	$keystbl=$_POST['keys'];
		if(strlen($keystbl)>0) {
			$keysobj=json_decode($keystbl);	
			if(gettype($keysobj)=='object') {
				if(strlen($keysobj->kodrasdel)){
                    for($i=0;$i<count($data);$i++) {$data[$i]->kodrasdel = $keysobj->kodrasdel;}
				}
				if(strlen($keysobj->kodmenu)){
                    for($i=0;$i<count($data);$i++) {$data[$i]->kodmenu=$keysobj->kodmenu;}
				}
				$wheresort=' WHERE kodrasdel='.$keysobj->kodrasdel.' AND kodmenu='.$keysobj->kodmenu.' ';
			}
		}
	include('class/var_alt.php');
	$key=array_search($tabl, $massTablAlias);
	
	if(strlen($key)>0) {$tablic=$key;
	include('class/init_table.php');
	include('class/window_save.php');
	$windsave= new WindowSave($tablic,$db);
	$fields=$windsave->allmasskey();
	
	//debug_to_console($data);
	$corrfields=$windsave->findfields($data[0]);
		if(strlen($corrfields)==0){
		$sorty=$windsave->maxval('sort', $wheresort)+1;
		for($i=0;$i<count($data);$i++) {
            $windsave->saver($data[$i], $sorty);
            $sorty=$sorty+1;
        }
		$mass[0]['atribut']=1;$mass[0]['text']="OK.";
		//debug_to_console($sorty);		
		}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с полями.".$corrfields;}
	}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с таблами.";}
	
	//debug_to_console($data);
	
	}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с правами.";}


echo json_encode($mass);	
?>