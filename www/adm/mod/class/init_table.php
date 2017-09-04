<?
class InitTable{ //класс таблица инициализация дааных + полей
	private $sql;
	private $stmt;
	private $masskey=array();
	private $countfields=0;
	private $massvalue=array();
	private $db;
	private $nametabl;
	private $aliastabl;
	private $out_masskey=array();
	
	
	public function __construct($nmtbl,$dbh) { // получение запроса и вывод колва полей
	// $this->sql ="SELECT * FROM ".$nametabl."";
	$this->db=$dbh; $this->nametabl=$nmtbl;
	$this->sql ="SHOW COLUMNS FROM ".$this->nametabl."";
	 $this->stmt = $this->db->prepare($this->sql);
	 $this->stmt->execute();
	 $this->countfields=$this->countrecord();
	 }
	 
	private function countrecord(){ // подсчет полей и в массив
		$i=0;
		if($sms=$this->stmt->fetchAll(PDO::FETCH_OBJ)) {
			//debug_to_console($sms );
			foreach($sms as $key=>$value){
			$this->masskey[$i]=$sms[$i]->Field; 
			$i++;
			//debug_to_console($masskey );
			}
		return $i;
		}
	return count($this->masskey);	 
	}
	public function outmasskey($massnametbl,$massAssoc){ // выводимые имена полей
		//debug_to_console("-----" );debug_to_console($massnametbl ); debug_to_console($this->countfields );
		if($this->countfields>0) {
			//debug_to_console($massnametbl);
			$this->out_masskey=array('kod'=>'Код');
				foreach ($massnametbl as $value){
					if(array_key_exists($value, $massAssoc)>0 && array_search($value, $this->masskey)>0) {
					$this->out_masskey[$value]=$massAssoc[$value];	
					}//if
				} if(count($this->out_masskey)<2)  $this->out_masskey=array();
			}else {debug_to_console('no fields. maybe a wrong name tablic');}
		}
	
	public function countrec($cause){ // подсчет записей и в массив здесь будет условие
	//debug_to_console($this->masskey() );
	$tempkey=$this->masskey();
		if (count($tempkey)>0){
			$this->sql ="SELECT * FROM ". $this->nametabl.$cause. " Order by sort";
			//debug_to_console($this->sql );
			 $this->stmt = $this->db->prepare($this->sql);
			$this->stmt->execute(); $k=0;
			foreach($this->stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
				foreach($tempkey as $key=>$value){
				$this->massvalue[$k][$key]= $row[$key];
				}
			
			$k++;
			} return(count($this->massvalue));
		}else{return(0);}
	}
	
	
	
	public function masskey() { //массив выводимыx полей
	   return $this->out_masskey;
	}
	public function allmasskey() { //массив всех полей
	   return $this->masskey;
	}
	public function massvalue() { //массив записей
	   return $this->massvalue;
	}
	public function nametabl() { //название таблицы
	   return $this->nametabl;
	}
	public function db() { //ссылка на базу
	   return $this->db;
	}
	public function __destruct() { //деструктор
	   $this->stmt=null;
	   // (очистить память)
	 }
	public function out(){
		debug_to_console($this->out_masskey);
		}
	
	public function aliasTbl(){ //алиас таблицы
		include('var_alt.php');
		$this->aliastabl='';
			foreach ($massTablAlias as $key=>$value){
			 if($this->nametabl==$key) {$this->aliastabl=$value;} 
			}
			//debug_to_console($massTablAlias );
	return $this->aliastabl;
	}	
	
	
	
	

}
?>