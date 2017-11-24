<?
class MenuTable extends InitTable { // дочерний класс меню вывод таблицы-----------------------------------------
private $arr_buttons=array();
private $alias;
public $tbl_img;
public $style='';

	public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }
		
	public function out(){
		debug_to_console($this->massvalue() );
		}
	
	public function outtitle($title) {  // вывод заголовка
		return ('<div class="breadcrumb namtbl">'.$title.'</div>');
		}
	
	public function createButton($massbutt){ // получить массив кнопок действий
		if(gettype($massbutt)=='array') $this->arr_buttons=$massbutt;
		}
	public function buttSortReturn() {  // вывод заголовка
		return ($this->buttSort());
		}		
	
	
	public function createOut($massTypField, $selTabl, $paramhref){ // вывод заголовка таблицы и полей
	$this->alias=$this->aliasTbl();
	$outtabl='<table id="'.$this->alias.'" class="table table-striped" '.$this->style.'>';
	$outtabl.=	$this->zagolOut().$this->recordOut($massTypField, $selTabl, $paramhref).'</table>';
	//debug_to_console($this->zagolOut());	
	//debug_to_console($this->recordOut($massTypField, $selTabl, $paramhref));	
	//$buttons=$this->buttSort();
	return $outtabl;
	}
	
	private function zagolOut(){ // обработка массива заголовка 
		$temp='';
		foreach($this->masskey() as $key=>$value){
		if($key!='kod')$temp.='<th class="tabl'.$key.'">'.$value.'</th>';
		}
	return '<tr>'.$temp.'<th class="tablcentr sorty">Порядок</th><th class="tablcentr action">Действие</th></tr>';
	}
	
	
	private function buttSort() { // поля с кнопками действий и сортировки
	$butt='';
	$sort='<td class="tablcentr sorty"><i name="sortarrowup" class="glyphicon glyphicon-arrow-up"></i> <i name="sortarrowdown" class="glyphicon glyphicon-arrow-down"></i></td>';
		if (count($this->arr_buttons)>0) {
			$main=0; $buttspisok='';
			// debug_to_console ($this->arr_buttons);
				foreach($this->arr_buttons as $key=>$value){
					if(!$main) {$butt="<div  class='btn-group'> <button name='knopa' type='button' class='btn btn-default editbutt'".
					" onclick='".$value."(event);'>".$key."</button>";
					$buttspisok=" <button type='button' class='btn btn-default dropdown-toggle editbutt' data-toggle='dropdown'>".
					"<span class='caret'></span> <span class='sr-only'>Меню с переключением</span> </button>  <ul class='dropdown-menu' role='menu'>"; 
					$main=1;
					} else {$buttspisok.="<li><a href='#' onclick='".$value."(event);'>".$key."</a></li>";//\"[_NOM]\"
					}// if
				}//foreach
		if (count($this->arr_buttons)>1) {$butt.=$buttspisok.'</ul></div>';} else{$butt.='</div>';}		
		}
		//$tmp='<td class="tablcentr buttons">'.$butt.'</td>';
		// debug_to_console ($butt);
		 //debug_to_console (array($sort,$butt));
  return array($sort,'<td class="tablcentr buttons col-xs-2">'.$butt.'</td>');
 
  }
	
	public function recordOut($massTypField, $selTabl, $paramhref){ // вывод записей таблицы
	//if(gettype($paramhref)=='array'){$switch=0; }else{$switch=-1;}
	if(strlen($paramhref)==0){$switch=0; }else{$switch=1;}
		$temp='';
		$buttons=$this->buttSort();
		//debug_to_console($buttons);
		foreach($this->massvalue() as $keyArr=>$valArr){
			$key_tmp='[_NOM]';
			$temp.='<tr id="nom'.$key_tmp.'">';
				foreach ($valArr as $key=>$value){
					if(array_key_exists($key, $massTypField)>0) {
						$typ=$massTypField[$key];
							switch ($typ){
							case 'checkbox':if($value) {$t='checked';} else {$t='';}
								$temp.='<td name="'.$key.'" class="tabl'.$key.'"><div class="checkbox"> '. 
								 '<input type="checkbox" value="" '.$t .' disabled> </div></td>' ;	
								//debug_to_console($temp); 
								break;
							
							case 'select':
                                //debug_to_console($selTabl);
                                if(is_array($selTabl) && $selTabl[$key]) {
                                    $zapr='SELECT * FROM '.$selTabl[$key].' WHERE kod='.$value;
                                    $dbl= $this->db();
                                    $stmt2 = $dbl->prepare($zapr);
                                    $stmt2->execute();
                                    if($sms=$stmt2->fetch(PDO::FETCH_ASSOC)) {$t=$sms['type'];} else {$t='';}
                                    $temp.='<td name="rol" class="tabl'.$key.'">'.$t.'</td>';
							    }else {$temp.='<td name="rol" class="tabl'.$key.'">не определен Select</td>';} break;
							
							case 'href': if ($switch){$paramHR=$paramhref.'[_NOM]';}else {$paramHR='?'.mb_strtolower($value,'UTF-8');}
							 $temp.='<td name="'.$key.'" class="tabl'.$key.'"><a href="'.$paramHR.'">'.$value.'</a></td>'; break;
							
							case 'kod': $kodnom=$value;// debug_to_console($buttons[1]); 
							$koder=str_replace('[_NOM]','nom'.$value, $buttons[1]);//$koder=str_replace('[_NOM]','nom'.$value, $buttons[1]);
							//echo $koder;
							 break;
							
							case 'picture':// $kodnom=$value;// debug_to_console($buttons[1]); 
							$temp.='<td name="'.$key.'" class="tabl'.$key.'">'.$this->countpictur($kodnom).'</td>';
							//debug_to_console($kodnom); 
							break;
                            case 'times':$temp.='<td name="'.$key.'" class="tabl'.$key.'">'.$this->time($value).'</td>';
                                break;
							}
					}else{
					
					$temp.='<td name="'.$key.'" class="tabl'.$key.'">'.$value.'</td>';
					}//if
				$temp=	str_replace('[_NOM]',$kodnom, $temp);
				}//foreach
				
		$temp.=$buttons[0].$koder.'</tr>';  $koder=''; 
		}//foreach
	return $temp;
	}	
	
	public function countpictur($nomer){ // подсчет картинок
		//debug_to_console($this->tbl_img); 
		if (strlen($this->tbl_img)>0 && $nomer>0) {
			$dbl= $this->db();
			$sql="SELECT COUNT(*) as count FROM ".$this->tbl_img." WHERE kodrasdel=".$nomer.";";
			$stmt = $dbl->prepare($sql);
			$stmt->execute();
		//	debug_to_console($sql); 
			if($sms=$stmt->fetch(PDO::FETCH_ASSOC)) { $tmp=$sms['count'];} else{$tmp=0;}
		} 
	return '<div>Всего: <span> '.$tmp.'</span></div>';
	}
	
	public function nameImgTbl(){ // имя таблицы картинок
	include('var_alt.php');
	$this->tbl_img=$picturTbl[$this->nametabl()];
	//debug_to_console($this->tbl_img); 
	}
    private function time($value)
    {
        $hh = floor($value / 3600);
        $min = floor(($value - $hh * 3600) / 60);
        $sec = $value - $hh * 3600 - $min * 60;
        if($hh==0){$hour='';}else{$hour=sprintf('%02d', $hh) . ':' ;}
        $l = $hour. sprintf('%02d', $min) . ':' . sprintf('%02d', $sec);

        return $l;
    }
}
?>