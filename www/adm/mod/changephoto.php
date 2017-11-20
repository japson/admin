<?
session_start();
require_once('conn/db_conn.php');
include('conn/UserCheck.php') ;
require_once('debug.php');
$arc=UserCheck();
//echo ('');
if(isset($_COOKIE['auth_key'])  and $arc[0]['atribut']==1)	{
		$data=json_decode($_POST['data']);
		if(gettype($data)=='object') {
			if(strlen($data->tbl)){$tabl=$data->tbl;}
			if(strlen($data->id)){$id=$data->id;}
			if(strlen($data->param)){$param=$data->param;}
			if(strlen($data->text)){$txt=$data->text;}
			if(strlen($data->kodpos)){$kodpos=$data->kodpos;}
            if(count($data->coords)){$coords=$data->coords;}
            if(strlen($data->ugol)){$ugol=$data->ugol; $coords[]=$ugol;}

		}
		include('class/var_alt.php');
		$key=array_search($tabl, $massTablAlias);
		//debug_to_console($key);
		if(strlen($key)>0) {$tablic=$key;
			include('class/win_pictinput.php');
			include('class/win_pictchange.php');
			$windinput= new WindowChangePictur($tablic,$db);
			$windinput->imgAlias();
			$windinput->initChange($id,$param,$txt,$kodpos,$coords);
			$kolcurrent=$windinput->kolpict;
			$out=$windinput->massPictur($kodpos);
			$out.=$windinput->outAction('delOverley');
			$mass[0]['atribut']=1;$mass[0]['text']=$out;//$mass[0]['kol']=$kolcurrent;
		
		}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с таблицей.";}
}else {$mass[0]['atribut']=0;$mass[0]['text']="Извините, проблемы с правами.";}

echo json_encode($mass);	
?>