<?
class SortRecords extends InitTable{ // сортировка
	private $massoutfld=array();
	private $tblImg;
	
	public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }
	
	public function checkfields($inputdat){ // проверка полей
		$tmp='';
		$basefields=$this->allmasskey();
			foreach($inputdat as $key=>$value){
				if(!array_search($value, $basefields)) {$tmp='maybe no '.$value;}
				//else {$this->massoutfld[]=$value;}
			}
	return $tmp;
	}
	
	public function perevorot($current, $other){
		$currentmas=array(str_replace("nom", "", $current), str_replace("nom", "", $other));
		//debug_to_console($currentmas);
		$sortmas =array();
	$connect=$this->db();
	$namtbl=$this->nametabl();
	$sql ="SELECT sort FROM ". $namtbl." WHERE kod=?";
	$stmt = $connect->prepare($sql);
		foreach($currentmas as $key=>$value){
		$stmt->execute(array($value)); 
		$sms=$stmt->fetch(PDO::FETCH_ASSOC); 
		$sortmas[]=$sms['sort'];	
		}
		$reversed = array_reverse($sortmas); $i=0;
		$sql=' UPDATE '.$namtbl.' SET sort=? WHERE kod=?';
		$stmt = $connect->prepare($sql);
		foreach($currentmas as $key=>$value){
		$stmt->execute(array($reversed[$i],$value)); 	
		$i++;
			}	
	}
	
	
}

?>