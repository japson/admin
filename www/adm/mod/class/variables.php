<?
// массивы выводимых полей
$massAssoc=array("login" => "Логин","rol" => "Роль","email" => "E-mail","sort" => "№","name" => "Название","nameurl" => "ЧПУ page", "vyvod" => "Вывод", "artist" => "Артист", "title" => "Название", "put" => "Ссылка", "length" => "Длина", 'note'=>'Описание','external'=>'Внешняя', 'redirect'=>'InnerUrl', 'side'=>'Side','type'=>'Тип',
"href" => "Ссылка", "titlepage" => "Title page", "pssw"=>"Пароль", "parametr"=>"Параметр", "value"=>"Значение","info"=>"Описание",'pictur'=>'Picture');
$massTypField=array('vyvod'=>'checkbox','rol'=>'select','side'=>'select', 'type'=>'select','name'=>'href', 'kod'=>'kod', 'pssw'=>'password','pictur'=>'picture','external'=>'checkbox','redirect'=>'redirect','length'=>'times');
$editAbles=array('login','email','name','nameurl','href','title','rol','vyvod','pictur','title','artist','note','length','put','external', 'redirect','side','type');


switch($tablic) {
	case 'init': $userpunkt =array('sort','name','vyvod');  break; 
	case 'sets': $userpunkt =array('sort','parametr','value','info','vyvod');  
					 $inputpunkt =array('parametr','value','info');
					 $tbl_select='';
					 $mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord');
					 break; 
	
	case 'mainmenu': $userpunkt =array('sort','name','nameurl','titlepage','vyvod', 'rol','pictur');
					 $inputpunkt =array('name','nameurl','title', 'rol');
					 $tbl_select=array('rol'=>'typmenu');
					 $mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord');
					 break;
	case 'editors': $userpunkt =array('sort','login','email','vyvod', 'rol'); 
					$inputpunkt =array('login','pssw', 'email', 'rol');
					$tbl_select=array('rol'=>'typuser');
					$mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord','Изменить пароль'=>'changePass'); 
					break;
					
	case 'rasdel': $userpunkt =array('sort','name','nameurl', 'redirect','rol', 'vyvod','pictur');
					$inputpunkt =array('name','nameurl','rol');
					$tbl_select=array('rol'=>'typmenu');
					$mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord');
					break;
	case 'punkt': $userpunkt =array('sort','side','artist','title','length', 'put','external','pictur');
					$inputpunkt =array('artist','title','link');
					$tbl_select=array('side'=>'typside');
					$mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord');
					break;
    case 'news': $userpunkt =array('sort','name','nameurl','type','redirect','vyvod', 'pictur');
                $inputpunkt =array('name','nameurl');
                $tbl_select=array('type'=>'typarticle');;
                $mass_actions=array('Править'=>'editRecord','Удалить'=>'delRecord');
                break;
}


?>