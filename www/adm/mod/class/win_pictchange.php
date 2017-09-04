<?
class WindowChangePictur extends WindowInputPictur{ // изменение картинок
	private $id;
	private $param;
	private $txt;
	private $kodrasdel;
	public $kolpict;
	
	public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }
	
	public function initChange($id,$param,$txt,$kodrasdel) { // инициализация данных
		$this->id=str_replace('nom','',$id);
		$this->param=$param;
		$this->txt=$txt;
		$this->kodrasdel=$kodrasdel;
		$this->selectAction();
	}
	
	private function selectAction(){  // выбор действия
		$dbl=$this->db();
		if($this->param==2) { 	// удалить картинку
			$sql="SELECT * FROM ".$this->tblimg." WHERE kod=".$this->id.";"; // LIKE '%'" 	
			$stmt = $dbl->prepare($sql);
			$stmt->execute();
			if($sms=$stmt->fetch(PDO::FETCH_ASSOC)) {$namep=$this->putimg.$sms["name"];$namesm=$this->putimg.$sms["name_small"];
				unlink($namep);
				unlink($namesm);
				$sql="DELETE FROM ".$this->tblimg." WHERE kod='".$this->id."';"; // LIKE '%'"
				$stmt = $dbl->prepare($sql);
				$stmt->execute();
				$this->del_sort('kodrasdel');
				
			}
		}
		if($this->param==1) { 	// изменить  название
			$sql="UPDATE ".$this->tblimg." SET note =? WHERE kod = ?; "; // LIKE '%'" 
			//debug_to_console($sql);
			$stmt = $dbl->prepare($sql);
			$stmt->execute(array(htmlspecialchars($this->txt),$this->id));
		}
	}
	
	private function del_sort($polevybor) { // сортировка после удаления
		$sorty=array();
		$dbl=$this->db();	
		$sql="SELECT  kod FROM ".$this->tblimg." WHERE ".$polevybor."=? ORDER BY sort;";
		$stmt = $dbl->prepare($sql);
		$stmt->execute(array($this->kodrasdel));
		$k=0;
		if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
			foreach($sms as $row){
			$sorty[$k]["kod"]=$row["kod"];
			$k++;
			}
			//debug_to_console($sorty);
		}
		
		$sql="UPDATE ".$this->tblimg." SET sort =? WHERE kod = ?; "; // LIKE '%'" 
		$stmt = $dbl->prepare($sql);
		for($i=0;$i<count($sorty);$i++) {
			$k=$i+1;
			
			//$sql="UPDATE ".$this->tblimg." SET sort =".$k." WHERE kod = " . $sorty[$i]["kod"] . "; "; // LIKE '%'" 	
			
			$stmt->execute(array($k,$sorty[$i]["kod"]));
			
		}
		$this->kolpict=count($stmt);
	}

}
?>