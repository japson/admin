<?
class RasdelTable extends MenuTable{ // вывод редактируемых полей
	private $text;
	private $kodarticle;
	private $description;
	private $keysword;
	protected $massrec;
			
	public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }
	
	public function outText(){  // вывод статьи
		$mass=$this->massrec();
		
		$new='<div class="row textareablock" id="post"><textarea id="textID" name="textID">'.$mass[0]['post'].'</textarea></div>'; 
		$new.='<div class="row keywords" id="seones"><div>Ключевые слова</div><textarea id="keywords" class="seones" >'.$mass[0]['keywords'].'</textarea>';
		$new.='<div>Description (описание)</div><textarea id="description" class="seones" >'.$mass[0]['description'].'</textarea></div>';
	return $new;
	}
	
	public function initText($text,$kodarticle,$description,$keysword){ // инициализация переменных
		$this->text=$text;
		$this->kodarticle=$kodarticle;
		$this->description=$description;
		$this->keysword=$keysword;
	}
	
	public function changeText($action) {
		$dbl=$this->db(); $ok='';
		if($action==1 or $action==2){  // запись новости
			$sql="UPDATE ".$this->nametabl()." SET post =?, description=?, keywords=?, data=?  WHERE kod = ?";
			$stmt = $dbl->prepare($sql);
			$stmt->execute(array(htmlspecialchars($this->text),htmlspecialchars($this->description),htmlspecialchars($this->keysword), date('Y-m-d H:i:s'), $this->kodarticle ));
		}
		if($action==2){  // вывод новости новости
			$sql ="SELECT * FROM ". $this->nametabl().' WHERE kod = ?';
			//debug_to_console($sql );
			$stmt = $dbl->prepare($sql);
			$stmt->execute(array($this->kodarticle));
			foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
				$tmp=htmlspecialchars_decode($row['post']);
			}
			$butt=$this->butPage();
			$tmp=$this->rasdelPage($tmp);
			
			$ok='<div id="primer" class="primerclass">'.$tmp.'</div>'.$butt;
		}
		if($action==3){  // вывод редактирования новости
			$sql ="SELECT * FROM ". $this->nametabl().' WHERE kod = ?';
			//debug_to_console($sql );
			$stmt = $dbl->prepare($sql);
			$stmt->execute(array($this->kodarticle));
			foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
				$tmp=$row['post'];
			}
			
			$ok='<textarea id="textID" name="textID">'.$tmp.'</textarea>';
		}
		
	return	$ok;
	}
	
	private function rasdelPage($tmp) { // разделение страниц
		$pieces = explode("[_page]", $tmp); $book='';
			for($i=0;$i<count($pieces);$i++) {
				if($i==0){$styl='style="display:block; opacity:1;"';} else {$styl='style="display:none; opacity:0;"';}
			 $book.='<div id="page'.($i+1).'" class="stranic" '.$styl.'>'.$pieces[$i].'<div class="nompage">Страница '.($i+1).'</div></div>';
			}
	return $book;		
	}
	
	private function butPage(){ // кнопки переходов
		$temp='<div class=" buttprev"><i class="glyphicon glyphicon-arrow-left prev_page" id="left_page"></i><i class="glyphicon glyphicon-arrow-right next_page" id="right_page"></i></div>';
		return $temp;
	}
	
	public function countrecAll($cause){ // подсчет записей и в массив здесь будет условие
		$dbl=$this->db();
		$sql ="SELECT * FROM ". $this->nametabl().$cause. " Order by sort";
			//debug_to_console($this->sql );
		$stmt = $dbl->prepare($sql);
		$stmt->execute(); $k=0;
		foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
				foreach($row as $key=>$value){
					$this->massrec[$k][$key]= $row[$key];
				}
			$k++;
		} return(count($this->massrec));
	}
	
	public function massrec(){
		return $this->massrec;}
}
?>
