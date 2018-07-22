<?
session_start();
require_once('mod/debug.php');
require_once('mod/conn/db_conn.php');
require_once('mod/conn/db_logmain.php');

if ( $logged_in &&  $auth_in ) {
			$punkt=$_GET;
			foreach($punkt as $key=>$value){
				$punkt_key=$key; $punkt_value=$value;
			}
	switch ($punkt_key){	
	case 'menu': $men=$punkt_value;
				 if(preg_match("/^[0-9]+$/",$men)){
		 require_once('mod/class/create_menu.php');
		$menu= new createMenu(array('Создать раздел'=>'createSect','Создать статью'=>'createArticle'),$db);
	 	$menu->creatBreadMenu($men,'mainmenu');
		$alias=$menu-> namemenu;
		$bread=$menu->breadcrumb.$menu->menuout;
		$tablic='rasdel';
		include('mod/class/variables.php');
		include('mod/class/init_table.php');
		include('mod/class/menu_table.php');
		include('mod/class/rasd_table.php');
		$test=new RasdelTable($tablic,$db);
		$test->outmasskey($userpunkt,$massAssoc);
		$test->countrec(" WHERE kodmenu=".$men.' AND kodrasdel=0 ');
		$test->createButton($mass_actions);
		$vyvod=$test->outtitle('Разделы: '.$alias);
		$test->nameImgTbl();
		$vyvod.=$test->createOut($massTypField,$tbl_select, '?section=');
		$masj="{'table':'".$test->aliasTbl()."','kodrasdel':0,'kodmenu':".$men.",'alias':'Разделы: ".$alias."'}";
		$vyvod.='<script >table_auto("section",'.$masj.');</script >';
		
		$tablic='news';
		include('mod/class/variables.php');
		$test=new RasdelTable($tablic,$db);
		$test->outmasskey($userpunkt,$massAssoc);
		$count=$test->countrec("  WHERE kodmenu=".$men.' AND kodrasdel=0  ');
		if ($count==0){$test->style='style="display:none"';}
		else{$test->style='';$test->createButton($mass_actions);
		$vyvod.=$test->outtitle('Статьи: '.$alias);}
		
		$test->nameImgTbl();
		$vyvod.=$test->createOut($massTypField,$tbl_select, '?post=');
		$masj="{'table':'".$test->aliasTbl()."','kodmenu':".$men.",'kodrasdel':0,'alias':'Статьи: ".$alias."'}";
		$vyvod.='<script>table_auto("post",'.$masj.');</script>';
		
	 }else{	 
		require_once('mod/class/create_menu.php');
		$menu= new createMenu(array('Создать пункт'=>'createMenu','Создать статью'=>'createArticle'),$db);
		$menu->creatBread(2,'Menu');
		$bread=$menu->breadcrumb.$menu->menuout;
		$tablic='mainmenu';
		include('mod/class/variables.php');
		include('mod/class/init_table.php');
		include('mod/class/menu_table.php');
         include('mod/class/rasd_table.php');
		$test=new MenuTable($tablic,$db);
		$test->outmasskey($userpunkt,$massAssoc);
		$test->countrec("");
		//$test->createButton(array('Edit'=>'editRecord','Удалить'=>'delRecord','Изменить пароль'=>'changePass'));
		$test->createButton($mass_actions);
		$vyvod=$test->outtitle('Пункты меню');
		$vyvod.=$test->createOut($massTypField,$tbl_select, '?'.$test->aliasTbl().'=');
		$test->nameImgTbl();
		$masj="{'table':'".$test->aliasTbl()."','kodmenu':0,'kodrasdel':0,'alias':'Меню: ".$alias."'}";
		$vyvod.='<script>table_auto("menu",'.$masj.');</script>';

         $tablic='news';
         include('mod/class/variables.php');
         $test=new RasdelTable($tablic,$db);
         $test->outmasskey($userpunkt,$massAssoc);
         $count=$test->countrec('  WHERE kodmenu=0 AND kodrasdel=0  ');
         if ($count==0){$test->style='style="display:none"';}
         else{$test->style='';$test->createButton($mass_actions);
             $vyvod.=$test->outtitle('Статьи: '.$alias);}

         $test->nameImgTbl();
         $vyvod.=$test->createOut($massTypField,$tbl_select, '?post=');
         $masj="{'table':'".$test->aliasTbl()."','kodmenu':0,'kodrasdel':0,'alias':'Статьи: ".$alias."'}";
         $vyvod.='<script>table_auto("post",'.$masj.');</script>';
	 }
				
				break;
	
	case 'section': $men=$punkt_value;
					 if(preg_match("/^[0-9]+$/",$men)){
		// debug_to_console('--');
		require_once('mod/class/create_menu.php');
		$menu= new createMenu(array('Создать раздел'=>'createSect','Создать статью'=>'createArticle','Создать элемент'=>'createElem'),$db);
		$menu->creatBreadSect($men,'rasdel','rasdel');
		$alias=$menu->namemenu;
		$bread=$menu->breadcrumb.$menu->menuout;	 
		$tablic='rasdel';
		include('mod/class/variables.php');
		include('mod/class/init_table.php');
		include('mod/class/menu_table.php');
		include('mod/class/rasd_table.php');
		$test=new RasdelTable($tablic,$db);
		$test->outmasskey($userpunkt,$massAssoc);
		$test->countrec(" WHERE kodrasdel=".$men.' AND kodmenu='.$menu->menukod.' ');
		$test->createButton($mass_actions);
		$vyvod=$test->outtitle('Разделы: '.$alias);
		$test->nameImgTbl();
		$vyvod.=$test->createOut($massTypField,$tbl_select, '?section=');
		$masj="{'table':'".$test->aliasTbl()."','kodmenu':".$menu->menukod.",'kodrasdel':".$men.",'alias':'Разделы: ".$alias."'}";
		$vyvod.='<script>table_auto("section",'.$masj.');</script>';
		
		$tablic='news';
         include('mod/class/variables.php');
		$test=new RasdelTable($tablic,$db);
		$test->outmasskey($userpunkt,$massAssoc);
		$count=$test->countrec(" WHERE kodrasdel=".$men.' AND kodmenu='.$menu->menukod.' ');
		if ($count==0){$test->style='style="display:none"';}
		else{$test->style='';$test->createButton($mass_actions);
		$vyvod.=$test->outtitle('Статьи: '.$alias);}
		
		$test->nameImgTbl();
		$vyvod.=$test->createOut($massTypField,$tbl_select, '?post=');
		$masj="{'table':'".$test->aliasTbl()."','kodmenu':".$menu->menukod.",'kodrasdel':".$men.",'alias':'Статьи: ".$alias."'}";
		$vyvod.='<script>table_auto("post",'.$masj.');</script>';

         $tablic='punkt';
         include('mod/class/variables.php');
         $test=new RasdelTable($tablic,$db);
         $test->outmasskey($userpunkt,$massAssoc);
         $count=$test->countrec(" WHERE kodrasdel=".$men.' AND kodmenu='.$menu->menukod.' ');
         if ($count==0){$test->style='style="display:none"';}
         else{$test->style='';$test->createButton($mass_actions);
             $vyvod.=$test->outtitle('Пункты: '.$alias);}

         $test->nameImgTbl();
         $vyvod.=$test->createOut($massTypField,$tbl_select, '?post=');
         $masj="{'table':'".$test->aliasTbl()."','kodmenu':".$menu->menukod.",'kodrasdel':".$men.",'alias':'Пункты: ".$alias."'}";
         $vyvod.='<script>table_auto("position",'.$masj.');</script>';

	 }
				
				break;
				
	case 'post': $men=$punkt_value;
					 if(preg_match("/^[0-9]+$/",$men)){
				require_once('mod/class/create_menu.php');	
				$menu= new createMenu(array('Сохранить статью'=>'saveArticle','Посмотреть статью'=>'viewArticle'),$db);
				$menu->creatBreadSect($men,'rasdel','news');
				$alias=$menu->namemenu;
				$bread=$menu->breadcrumb.$menu->menuout;
				$tablic='news';	
				include('mod/class/variables.php');
				include('mod/class/init_table.php');
				include('mod/class/menu_table.php');
				include('mod/class/rasd_table.php');
				$test=new RasdelTable($tablic,$db);
				$test->outmasskey($userpunkt,$massAssoc);
				$test->countrecAll(" WHERE kod=".$men.'');
				//debug_to_console($test->massrec());	
				$vyvod=$test->outtitle('Статья: '.$alias);
				$vyvod.=$test->outText();
				$masj="{'table':'".$test->aliasTbl()."','kodmenu':".$menu->menukod.",'kodarticle':".$men.",'alias':'Статьи: ".$alias."'}";
				$vyvod.= '<script>table_auto("post",'.$masj.');editNew();</script>';   
					
					 }			
				break;
	case 'users':
		require_once('mod/class/create_menu.php');
		$menu= new createMenu(array('Создать пользователя'=>'createUser'),$db);
		$menu->creatBread(2,'Users');
		$bread=$menu->breadcrumb.$menu->menuout;
		$tablic='editors';
		include('mod/class/variables.php');
		include('mod/class/init_table.php');
		include('mod/class/menu_table.php');
		$test=new MenuTable($tablic,$db);
		$test->outmasskey($userpunkt,$massAssoc);
		$test->countrec("");
		$test->createButton($mass_actions);
		$vyvod=$test->outtitle('Пользователи');
		$vyvod.=$test->createOut($massTypField,$tbl_select, '?'.$test->aliasTbl().'=');
		$test->nameImgTbl();
		$masj="{'table':'".$test->aliasTbl()."','kodmenu':0,'kodrasdel':0,'alias':'Меню: ".$alias."'}";
		$vyvod.='<script>table_auto("users",'.$masj.');</script>';
				
				break;
	case 'settings':
				require_once('mod/class/create_menu.php');
		$menu= new createMenu(array('Создать параметр'=>'createParam'),$db);
		$menu->creatBread(2,'Settings');
		$bread=$menu->breadcrumb.$menu->menuout;
		$tablic='sets';
		include('mod/class/variables.php');
		include('mod/class/init_table.php');
		include('mod/class/menu_table.php');
		$test=new MenuTable($tablic,$db);
		$test->outmasskey($userpunkt,$massAssoc);
		$test->countrec("");
		$test->createButton($mass_actions);
		$vyvod=$test->outtitle('Настройки параметров');
		$vyvod.=$test->createOut($massTypField,'', '');
		$test->nameImgTbl();
		$masj="{'table':'".$test->aliasTbl()."','kodmenu':0,'kodrasdel':0,'alias':'Меню: ".$alias."'}";
		$vyvod.='<script>table_auto("settings",'.$masj.');</script>';
	
				break;
	default: 
		require_once('mod/class/create_menu.php');
		$menu= new createMenu('', $db);
		$menu->creatBread(1,"");
		$bread=$menu->breadcrumb.$menu->menuout;
		$tablic='init';
		include('mod/class/variables.php');
		include('mod/class/init_table.php');
		include('mod/class/menu_table.php');
		$test=new MenuTable($tablic,$db);
		$test->outmasskey($userpunkt,$massAssoc);
		$test->countrec("");
		$vyvod=$test->outtitle('Админ. меню');
		$vyvod.=$test->createOut($massTypField,'', '');
		
				break;
	}
	
	
	
}


// $dbh = null;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="css/css.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="../fancy/jquery.fancybox.min.css">
<script src="ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="js/auto.js"></script>
   <!-- <script src='https://www.google.com/recaptcha/api.js'></script>-->
<title>Административная часть</title>
</head>

<body>
<div class="container">
<? echo($perem);
echo($bread);
echo($vyvod);
?>

</div> <!--container--> 

<script src="js/jquery-3.1.1.min.js"></script>
<script src="../fancy/jquery.fancybox.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.mousewheel-3.0.6.pack.js"></script>
<script src="js/js_adm.js"></script>
<script src="js/jquery.Jcrop.js"></script>
</body>
</html>