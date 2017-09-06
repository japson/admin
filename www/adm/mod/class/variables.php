<?
// массивы выводимых полей
$massAssoc=array("login" => "Логин","rol" => "Роль","email" => "E-mail","sort" => "№","name" => "Название","nameurl" => "ЧПУ page", "vyvod" => "Вывод", "artist" => "Артист", "song" => "Название", "link" => "Ссылка", "length" => "Длина", 'note'=>'Описание',
"href" => "Ссылка", "title" => "Title page", "pssw"=>"Пароль", "parametr"=>"Параметр", "value"=>"Значение","info"=>"Описание",'pictur'=>'Picture');
$massTypField=array('vyvod'=>'checkbox','rol'=>'select', 'name'=>'href', 'kod'=>'kod', 'pssw'=>'password','pictur'=>'picture');
$editAbles=array('login','email','name','nameurl','href','title','rol','vyvod','pictur','song','artist','note','length','link');


switch($tablic) {
	case 'init': $userpunkt =array('sort','name','vyvod');  break; 
	case 'sets': $userpunkt =array('sort','parametr','value','info','vyvod');  
					 $inputpunkt =array('parametr','value','info');
					 $tbl_select='';
					 $mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord');
					 break; 
	
	case 'mainmenu': $userpunkt =array('sort','name','nameurl','title','vyvod', 'rol'); 
					 $inputpunkt =array('name','nameurl','title', 'rol');
					 $tbl_select=array('rol'=>'typmenu');
					 $mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord');
					 break;
	case 'editors': $userpunkt =array('sort','login','email','vyvod', 'rol'); 
					$inputpunkt =array('login','pssw', 'email', 'rol');
					$tbl_select=array('rol'=>'typuser');
					$mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord','Изменить пароль'=>'changePass'); 
					break;
					
	case 'rasdel': $userpunkt =array('sort','name','nameurl','vyvod', 'rol','pictur'); 
					$inputpunkt =array('name','nameurl');
					$tbl_select=array('rol'=>'typmenu');
					$mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord');
					break;
	case 'punkt': $userpunkt =array('sort','artist','song','length', 'note','pictur');
					$inputpunkt =array('artist','song','link');
					$tbl_select='';
					$mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord');
					break;
    case 'news': $userpunkt =array('sort','name','nameurl','vyvod', 'pictur');
                $inputpunkt =array('name','nameurl');
                $tbl_select='';
                $mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord');
                break;
}


?>