<?
session_start();
$menuser="";
$perem= ' 
<div class="row"> 
<div class="col-lg-3 offset1"><h4>Административная часть</h4> </div>
</div><div class="row"> 
<div class="col-lg-9 offset1">Вы работаете под пользователем \''. $_SESSION['login']. '\'. <button type="button" id="log_butt" onClick="logout_a();" class="btn btn-info">Выйти</button></div>
<div class="row"><div class="control-group offset2 " id="errorsave"> </div></div>
</div>';



//$menureturn='<ol class="breadcrumb ">             <li><a href="#">Главная</a></li>             <li><a href="#">Раздел</a></li>             <li class="active">Текущая статья</li>           </ol>';
//menumain('editors');
//menumain('mainmenu');
//menumain('proba');


/*



if($_SESSION['rol']==1) $menuser=' <li><a href="/admin/index.php?user">Пользователи</a></li>';

$menu='<div class="row">
		
        <ul class="nav nav-pills nav-stacked col-md-2">'.$menuser.' 
		<li><a href="/admin/index.php?news">Новости</a></li>
       <li><a href="/admin/index.php?category">Разделы</a></li>
	   <li><a href="/admin/index.php?other">Дополнительно</a></li>
       </ul> 
  </div>';

// массивы выводимых полей
$massAssoc=array("login" => "Логин","rol" => "Роль","email" => "E-mail","sort" => "№","name" => "Название","nameurl" => "ЧПУ page", "vyvod" => "Вывод",
"href" => "Ссылка", "foo" => "bar");
$massTypField=array('vyvod'=>'checkbox','rol'=>'select', 'name'=>'href', 'kod'=>'kod');

$menupunkt =array('sort','name','nameurl','vyvod', 'rol');
$userpunkt =array('sort','login','email','vyvod', 'rol');




include('class/init_table.php');
include('class/menu_table.php');

//$test=new InitTable('editors',$db);
//$test->outmasskey($userpunkt,$massAssoc);
//$test->out();
//echo $test->countrec();
//unset($test);

//$test2=new MenuTable('mainmenu',$db);
//$test2->outmasskey($menupunkt,$massAssoc);
$test2=new MenuTable('editors',$db);
$test2->outmasskey($userpunkt,$massAssoc);
//$test2->masskey();

$test2->countrec();
$test2->out();
$test2->createButton(array('Edit'=>'editRecord','Удалить'=>'delRecord','Изменить пароль'=>'changePass'));
$vyvod=$test2->createOut($massTypField,'typuser', '');



	

*/




?>