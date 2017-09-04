<?
class WindowSave extends InitTable{  // запись данных в таблицу после проверки

	public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }
	
	public function findfields($obdata){ // ссответствие полей приема и прихода
		$tmp="";
		$fields=$this->allmasskey();
		 	foreach($obdata as $key=>$value){
			if(array_search($key, $fields)==0) {$tmp.='not field '.$key.' % ';}	
			}
	return $tmp;
	}
	
	public function maxval($field, $wheresort) { // макс поле по значению
		$connect=$this->db();
		$sql ="SELECT ".$field." FROM ". $this->nametabl().$wheresort." ORDER BY  ".$field." DESC";
		//$sql ="SELECT sort FROM editors ORDER BY sort DESC";
		$stmt = $connect->prepare($sql);
		$stmt->execute();
		if($sms=$stmt->fetch(PDO::FETCH_ASSOC)) {$maxznach=$sms[$field];}
		else {$maxznach=0;}
		//debug_to_console($sms);
		return $maxznach;
	}
	
	public function saver($obdata, $sorty){
		$connect=$this->db();
		$znach=array();
		$keys=array(); $tmp=array();
		//INSERT INTO tbl_name (col1,col2) VALUES(15,col1*2);
			foreach($obdata as $key=>$value){
				$keys[]=$key; $znach[]=htmlspecialchars($value); $tmp[]='?';
			}
			$keys[]='sort'; $znach[]=$sorty; $tmp[]='?';
		$sql='INSERT INTO '. $this->nametabl().' ('.implode(',',$keys).') VALUES ('.implode(',',$tmp).')';
		$stmt = $connect->prepare($sql);
		$stmt->execute($znach); 
		
		//$dbh->lastInsertId(); 
		//debug_to_console($sql);
	}
}
?>