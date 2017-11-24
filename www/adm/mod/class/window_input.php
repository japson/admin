<?
class WindowInput extends InitTable{ // окно ввода полей
	
	private $functAction;
	public $aliasform;
	
	public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }
	public function createHeader(){ // создать заголовок формы
		if(strlen( $this->aliasform)){
			$tmp=$this->aliasform; //mb_strtoupper(,'UTF-8')
			$tmp='<div class="row zagform">'.$tmp.'</div>';
		} else {$tmp='';}
	return $tmp;
	}
	
	public function createFields($typmenu) { // создание полей для окна ввода
		$massnames=$this->masskey();
		$form='';
		$tbl=$this->aliasTbl();
		include('variables.php');
			if (count($massTypField)>0){
			$zag=$this->createHeader();	
			$form=$zag."<form class='form-horizontal' name='inputdate' role='form'><label class='errorcursiv' id='errform'></label> ";
				foreach($massnames as $key=>$value){
						$typ=$massTypField[$key];
						$temp=$this->formField($typ, $typmenu);
						$str = mb_strtolower($value,'UTF-8');
						$temp=str_replace('[_VALUE]',$str,$temp);
						$temp=str_replace('[_FIELD]',$key,$temp);
						//debug_to_console($temp);
						$form.=$temp; $temp="";
				}
			}else {debug_to_console('no array types of fields');}
	return $form.'</form>';
	}
	
	private function formField($field, $typmenu){ // формирование типов полей
	$temp='';
		switch ($field){
			case 'checkbox': $temp="<div class='form-group ' id='[_FIELD]'><label class='control-label col-xs-4' for='name[_FIELD]'>Выберите [_VALUE]: <input type='checkbox' value=''> </label>"; break;
			case 'select':$temp="<div class='form-group ' id='[_FIELD]'><label class='control-label col-xs-4' for='name[_FIELD]'>Выберите [_VALUE]: 	</label> "; 
			$temp.='<div class="col-xs-7"><select class="form-control" name="[_FIELD]" id="[_FIELD]">'.$this->selectField($typmenu).'</select></div></div>';
			break;
			case 'password': $temp="<div class='form-group ' id='[_FIELD]'> <label class='control-label col-xs-4' for='name[_FIELD]'>Введите [_VALUE]: </label> <div class='col-xs-7'><input type='password' class='form-control' id='[_FIELD]' class='editpolesong' value=''></div></div>"; break;
			case 'kod': $temp=''; break;
			default: $temp="<div class='form-group ' id='[_FIELD]'> <label class='control-label col-xs-4' for='name[_FIELD]'>Введите [_VALUE]: </label><div class='col-xs-7'><input type='text' class='form-control' id='[_FIELD]' class='editpolesong' value=''></div></div>" ;
		}
	return $temp;
	}

	private function selectField($typmenu) { // формирование пункта select
		$zapr=""; 
		$connect=$this->db();
		$sql ="SELECT * FROM ". $typmenu."";
		$stmt = $connect->prepare($sql);
		$stmt->execute();
			if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
				//debug_to_console($sms);
				foreach($sms as $row){
				$zapr.='<option value="'.$row['kod'].'">'.$row['type'].'</option>';
				}
			}
		$stmt=null;	
	return $zapr;
	}
	
	public function inputAction($tmp){ //ввод функции действия
		$this->functAction=$tmp;
		}
	
	public function outAction(){ //вывод функции действия
	$tbl=$this->aliasTbl();
		if (strlen($this->functAction)>0) {
		$temp='<div class="col-sm-3 col-sm-offset-3"><button class="btn btn-success" onclick="{'.$this->functAction.'(\''.$tbl.'\');}">OK</button></div>';
		$temp.='<div class="col-sm-3"><button class="btn btn-warning" onclick="{delOverley();}">Отмена</button></div>';
			}
	return $temp;	
	}


    }

?>