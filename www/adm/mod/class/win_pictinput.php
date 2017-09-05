<?
class WindowInputPictur{ // окно ввода картинок
	private $db;
	private $nametabl;
	protected $putimg;
	private $alias;
	protected $tblimg;
	private $tblimgalias;
	
	public function __construct($nmtbl,$dbh) {
       $this->db=$dbh; $this->nametabl=$nmtbl;
	   $this->recievePut();
	   $this->inputAlias();
    }
	
	private function recievePut(){ // получить путь к картинкам
		include('var_alt.php');
		$this->putimg=$picturKat[$this->nametabl];
	}
	
	public function imgAlias(){ //получить таблицу картинок и алиас
		include('var_alt.php');
		$this->tblimg=$picturTbl[$this->nametabl];
		$this->tblimgalias=$massTablAlias[$this->tblimg];
		}
	
	public function inputAlias(){ // алиас  таблицы
		include('var_alt.php');
		$this->alias='';
			foreach ($massTablAlias as $key=>$value){
			 if($this->nametabl==$key) {$this->alias=$value;} 
			}
	}	
	public function massPictur($kodrasdel){
		$kod=str_replace('nom','',$kodrasdel);
		$tr_up=''; $tr_down='';
		$sql="SELECT * FROM ".$this->tblimg." WHERE kodrasdel=".$kod." ORDER BY sort ;"; 
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$mass_pict='<div class="divtblpict" ><p></p><table id="'.$this->alias.'" class="table"><tbody>';
			foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
				$tr_up.='<td id='.$row["kod"].'><a href="'.$this->putimg.$row["name"].'" class="photo img-thumbnail" data-fancybox="group" title="'.$row["note"].'"><img src="'.$this->putimg.$row["name_small"].'"  >	</a></td>';
				$tr_down.='<td id='.$row["kod"].'> <i id="pict_pencil" class="glyphicon glyphicon-pencil" title="Редактировать название"></i><i id="pict_left" class="glyphicon glyphicon-arrow-left" title="Переместить влево"></i><i id="pict_right" class="glyphicon glyphicon-arrow-right" title="Переместить вправо"></i><i id="pict_del" class="glyphicon glyphicon-remove" title="Удалить"></i></td>';	
			}
		$mass_pict.='<tr class="pict-up-thumbs" id=nom'.$kod.'>'.$tr_up.'</tr><tr class="pict-down-ikon" id=nom'.$kod.'>'.$tr_down.'</tr></tbody></table></div>';	
		
		$perem='<div id="downformvvod"><form class="form-horizontal" action="moduls/addfoto.php" method="post" id="form_add_foto" enctype="multipart/form-data">
		<label class="errorcursiv" id="errform"></label>
      <div class="form-group "><label class="control-label col-sm-4" for="namepict">Введи название:</label> <div class="col-sm-8">  <input type="text" class="form-control" name="nom'.$kod.'" id="namepict"></div></div>
	  <div class="form-group "><label class="control-label col-sm-4" for="namefile">Введи название файла(english):</label> <div class="col-sm-8">  <input type="text" class="form-control" name="'.$kodrasdel.'" id="namefile"></div></div>
      <div class="form-group "><label class="control-label col-sm-4" for="picture">Выбери файл:</label><div class="col-sm-8"> <input type="file" name="picture" id="picture"></div></div> 
      <div class="form-group col-sm-12 "><button class="btn btn-default col-sm-offset-10 " id="nosubmit" >Загрузить</button>  </div>   </form></div>';
	 // $mass[0]=1;$mass[1]=$mass_pict;$mass[2]=$perem;
	  return $mass_pict.$perem;
	}
	
	public function outAction($funct){ //вывод функции действия
	//$tbl=$this->aliasTbl();
		if (strlen($funct)>0) {
		$temp='<div class="buttpictform"><div class="col-sm-3 col-sm-offset-3"><button id="reservdata" class="btn btn-success" onclick="{'.$funct.'(\''.$this->alias.'\');}">OK</button></div>';
		$temp.='<div class="col-sm-3"><button class="btn btn-warning" onclick="{delOverley();}">Отмена</button></div></div>';
		$masj="{'table':'".$this->alias."'}"; //','put':'".$this->putimg."'
		$temp.='<script >table_auto("namepict",'.$masj.');</script >';
			}
	return $temp;	
	}
	
	public function db(){return $this->db;}
	public function nametabl(){return $this->nametabl;}
	public function tblimg(){return $this->tblimg;}
}
?>