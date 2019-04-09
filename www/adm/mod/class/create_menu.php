<?
class createMenu{
	public $menuout;
	public $breadcrumb;
	private $db;
	private $massmenu=array();
	public $namemenu;
	private $switchactive=0;
	public $menukod;
			
	public function __construct($punkts,$dbh) { // создание меню
		$this->db=$dbh;
		if(gettype($punkts)=='array') {
		$this->menuout='<div class="row" id="actionmenu"><ul class="nav nav-pills col-md-12">';
			foreach($punkts as $key=>$value){
			$this->menuout.='<li><a id='.$value.' href="#" onClick="'.$value.'('.$uroven.'); return false;">'.$key.'...</a></li>';
			}	
		$this->menuout.='</ul></div>';	
		
		} else {$this->menuout='<div class="row"><ul class="nav nav-pills col-md-5"><li><a href="#">&nbsp;</a></li></ul></div>';}
	}
	
	public function creatBread($uroven,$rasdel){ // создание крошек
		if($uroven==1) $this->breadcrumb='<ol class="breadcrumb "><li class="active">Главная</li>  </ol>';
		$main='<li class="active"><a href="/adm">Главная</a></li>';	
		if($uroven==2) $this->breadcrumb='<ol class="breadcrumb ">'.$main.'<li class="active">'.$rasdel.'</li>  </ol>';	
	}
	
	public function creatBreadMenu($uroven,$tabl){ // создание крошек из меню
			$this->massmenu[]='<li class="active"><a href="/adm">Главная</a></li>';	
			$tmp=$this->getAlias($tabl);
			
			if(strlen($tmp)>0){$this->massmenu[]='<li class="active"><a href="/adm/?'.$tmp.'">'.ucfirst($tmp).'</a></li>';}
			$sql ="SELECT name FROM ".$tabl." WHERE kod=?";
			$stmt = $this->db->prepare($sql);
			$stmt->execute(array($uroven));
				if($sms=$stmt->fetch(PDO::FETCH_ASSOC)) {
					$this->massmenu[]='<li class="active">'.$sms['name'].'</li>';
					$this->namemenu=$sms['name'];
					//debug_to_console($sms['name']);
				}
			$this->breadcrumb= 	'<ol class="breadcrumb ">'.implode('',$this->massmenu).'</ol>';
	}	
	
	public function creatBreadSect($uroven,$tabl,$tablpos){ // создание крошек из раздела
		$crumb=array(); $kodrasd=$uroven;
				if ($tablpos!=$tabl){  // если это статья
					$alias=$this->getAlias($tablpos);
					$sql ="SELECT * FROM ".$tablpos." WHERE kod=?";
					$stmt = $this->db->prepare($sql);
					$stmt->execute(array($kodrasd));
					if($sms=$stmt->fetch(PDO::FETCH_ASSOC)) {
						$crumb[]=$this->createLi($sms['name'],$alias,$kodrasd);
						$kodrasd=$sms['kodrasdel'];
						$kodmenu=$sms['kodmenu'];
					} else {$kodrasd=0;}
					}
		
		$alias=$this->getAlias($tabl);  // здесь с разделами
		$sql ="SELECT * FROM ".$tabl." WHERE kod=?";
		$stmt = $this->db->prepare($sql);
			while($kodrasd>0){
				$stmt->execute(array($kodrasd));
					if($sms=$stmt->fetch(PDO::FETCH_ASSOC)) {
						$crumb[]=$this->createLi($sms['name'],$alias,$kodrasd);
						$kodrasd=$sms['kodrasdel'];
						$kodmenu=$sms['kodmenu'];
					} else {$kodrasd=0;}
			}
			$this->menukod=$kodmenu;
			//$crumbrev=array_reverse($crumb);
		$tabl='mainmenu';	
		$alias=$this->getAlias($tabl);	
		$sql ="SELECT * FROM ".$tabl." WHERE kod=?"; // menu table
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array($kodmenu));
			if($sms=$stmt->fetch(PDO::FETCH_ASSOC)) {
				$crumb[]=$this->createLi($sms['name'],$alias,$kodmenu);
				}
		$crumb[]=$this->createLi($alias,$alias,0);
		$crumb[]='<li class="active"><a href="/adm">Главная</a></li>';
	
		$crumbrev=array_reverse($crumb);
		$this->breadcrumb= 	'<ol class="breadcrumb ">'.implode('',$crumbrev).'</ol>';
	}
	
	
	
	
	private function createLi($name,$tabl,$kod) { //создание кода li каждого
	mb_internal_encoding("UTF-8"); // обязателньо для русской первой в верхний регистр
			if($this->switchactive==0) {
				$temp='<li class="active">'.$this->mb_ucfirst($name).'</li>';
				$this->namemenu=$this->mb_ucfirst($name);
				$this->switchactive=1;
			}else{
				if($kod>0) {$temp='?'.$tabl.'='.$kod;} else {$temp='?'.$tabl;}
				$temp='<li class="active"><a href="/adm/'.$temp.'">'.$this->mb_ucfirst($name).'</a></li>';
			}
	return $temp;		
	}
	
	private function mb_ucfirst($text) {  // русские буквы в верхний регистр
    	return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
	}
	
			
	private function getAlias($tabl){ //получение алиаса для меню
		include('var_alt.php');
		$aliastabl='';
			foreach ($massTablAlias as $key=>$value){
			 if($tabl==$key) {$aliastabl=$value;} 
			}
	return $aliastabl;
	}
	
	public function punktOther($current){ // взять глубину
		debug_to_console($this->db);
	}
			
	
}

?>