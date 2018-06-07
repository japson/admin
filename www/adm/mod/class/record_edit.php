<?
class RecordEdit extends MenuTable{ // вывод редактируемых полей
	private $massdata=array();
	private $massoutfld=array();
	public $kodmenu;
	public $kodrasdel;
	
	
	public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }
	
	public function createfields($inputdat){ // создать массив полей из ключей
	$tmp=array();
	foreach($inputdat as $key=>$value){
		$tmp[]=$key;
		}
	return $tmp;
	}
	
	public function checkfields($inputdat){ // проверка полей
		$tmp='';
		$basefields=$this->allmasskey();
        //debug_to_console($inputdat);
			foreach($inputdat as $key=>$value){
				if(!array_search($value, $basefields)) {$tmp='maybe no '.$value;}
				else {$this->massoutfld[]=$value;}
			}
		return $tmp;
	}
	
	public function createEditableFields($tablic, $record, $typmenu){ // создание кода вывода инпутов
		$rec=str_replace("nom", "", $record);
		$connect=$this->db();
		$znach=array();
		$form='';$arrCurrent=array();
		$field_out=implode(', ',$this->massoutfld);
		$sql ="SELECT ".$field_out." FROM ". $this->nametabl()." WHERE kod=?";
		$stmt = $connect->prepare($sql);
		
		$stmt->execute(array($rec));
		$sms=$stmt->fetch(PDO::FETCH_ASSOC);
			include('variables.php');
			foreach($sms as $key=>$value){
				//debug_to_console($userpunkt);
				$typ='';
				if(array_search($key, $editAbles) ){
					$typ=$massTypField[$key];}
				if($key=='sort')$typ='sort';	
					$temp=$this->formField($typ, $typmenu, $value, $key,$rec);
					//$str = mb_strtolower($value,'UTF-8');
					$form=str_replace('[_VALUE]',htmlspecialchars($value),$temp);
					$this->massdata[$key]=$form; $temp="";
			}
		//	debug_to_console($this->massdata);
	}
	
	
	public function createOutFields($tablic,$record,$buttons){ // формируем вывод
		include('variables.php');
		foreach($userpunkt as $key=>$value){
			$mass[$value]= $this->massdata[$value];
			}
		 $outtabl='<tr id="'.$record.'">'.implode(' ',$mass).implode(' ',$buttons).'</tr>';
		// debug_to_console($outtabl);
		return ($outtabl);
	}
	
	private function formField($field, $typmenu,$valcur, $key, $rec){ // формирование типов полей инпутов
	$temp='';
		switch ($field){
			case 'checkbox': if($valcur==0){$t='';}else{$t='checked';}
				$temp='<td name="'.$key.'" class="tabl'.$key.'"><div class="checkbox"> '. 
								 '<input type="checkbox" value="" '.$t .' > </div></td>' ;break;
			case 'href': $temp.='<td name="'.$key.'" class="tabl'.$key.'"><input type="text" value="[_VALUE]"></input></td>'; break;
			case 'select': //$temp='<select class="form-control" name="'.$key.'" id="'.$key.'">'.$this->selectField($typmenu).'</select> ';
			$temp.='<td name="'.$key.'" class="tabl'.$key.'"><div class="selectcenter"><select class="form-control" name="'. $key.'" id="'. $key.'">'.$this->selectField($typmenu, $valcur,$key).'</select></div></td>';break;
			case 'sort': $temp.='<td name="'.$key.'" class="tabl'.$key.'">[_VALUE]</td>';break;
			case 'picture': $temp.='<td name="'.$key.'" class="tabl'.$key.'">'.$this->countpictur($rec).'</td>';;break;
            case 'redirect':
                if($valcur>0) {$redir='Есть';} else{$redir='Нет';}
                $temp.='<td name="'.$key.'" class="tabl'.$key.'"><div>'.$redir.'</div></td>';;break;
			default: $temp.='<td name="'.$key.'" class="tabl'.$key.'"><input type="text" value="[_VALUE]"></input></td>';break;
		}
		//debug_to_console($temp);
	return $temp;
	}
	
	private function selectField($typmenu, $valcur,$key) { // формирование пункта select
       // debug_to_console($typmenu);
        if(is_array($typmenu) && $typmenu[$key]) {
            $zapr = "";
            $connect = $this->db();
            $sql = "SELECT * FROM " . $typmenu[$key] . "";
            $stmt = $connect->prepare($sql);
            $stmt->execute();
            if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                //debug_to_console($sms);
                foreach ($sms as $row) {
                    if ($row['kod'] == $valcur) {
                        $t = 'selected';
                    } else {
                        $t = '';
                    }
                    $zapr .= '<option value="' . $row['kod'] . '"' . $t . '>' . $row['type'] . '</option>';
                }
            }
            $stmt = null;
        } else{$zapr .='не определен Select';}
	return $zapr;
	}
	
	public function saveOutFields ($data, $record){ // запись данных строки
	$rec=str_replace("nom", "", $record);
	$connect=$this->db();
	//$connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	$keys=array(); $znach=array();
	// UPDATE persondata SET age=age*2, age=age+1;
			foreach($data as $key=>$value){
				$keys[]=htmlspecialchars($key).'=?'; $znach[]=$value; 
				$t[]=gettype($key);
			}
			$znach[]=$rec;
		$sql=' UPDATE '.$this->nametabl().' SET '.implode(',',$keys).' WHERE kod=?';	
		//debug_to_console($sql);
		$stmt = $connect->prepare($sql);
		$stmt->execute($znach); 
	//debug_to_console($keys);
	//debug_to_console(count($znach));
	}
    public function checkDel($record,$pole)  // проверка можно ли удалять
    {   $tbl=$this->nametabl();
        include('var_alt.php');
        $avaltbl=$massLink[$tbl];
        $avalpctr=$picturTbl[$tbl];
        $pusto=1;
        if(is_array($avaltbl)) {foreach ($avaltbl as $key=>$value) { //вызвать для подчиненных
            if ($value == 0) {
                $temptabl = new RecordEdit($key, $this->db()); // если нет подчиненных разделов
                $temptabl->kodrasdel = $this->record;
                $temptabl->kodmenu = $this->kodmenu;
                 // debug_to_console($value);
                $temp = $temptabl->checkDel($record, 'kodrasdel');

            }
            if ($value == 1) {
                $pusto = $this->checkBrothers($record, $key);
            }
        }
        }else{$pusto=0;}
        $masspole=$this->massPole($record, $pole);
       // debug_to_console($avalpctr.' '.$pusto);
        if (strlen($avalpctr)) { $put=$picturKat[$tbl];//удалить из табл картинок и картинки
           // debug_to_console($avalpctr.'--'.$tbl.'>> '.$put);
            if($avalpctr==$tbl) {$this->clearPict($avalpctr,$masspole,$pole,$put);} // если сама картинко
            else {$this->clearPict($avalpctr,$masspole,'kodrasdel',$put);}
        }

        if($avalpctr!=$tbl && !$pusto) {$this->delCur($record,$pole);} // если сама не картинко удалить запись
        $this->del_sort(' WHERE kodrasdel='.$this->kodrasdel);
        return $temp;
    }
    private function massPole($rasdel,$pole){
	    $mass=array();
	    $tbl=$this->nametabl();
        $connect=$this->db();
        $sql="SELECT  * FROM ".$tbl." WHERE ".$pole."=".$rasdel.";";
        $stmt = $connect->prepare($sql);
        $stmt->execute();
        if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($sms as $row) {
                array_push($mass,$row['kod']);
            }
        }

    return $mass;
    }


    private function checkBrothers($record,$tabl){
        $connect=$this->db();
        $sql="SELECT COUNT(*) as count FROM ".$tabl." WHERE kodrasdel=".$record; // LIKE '%'"
        $stmt=$connect->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row=$stmt->fetch();
        $members=$row['count'];
        // debug_to_console($members);
        //$sql="SELECT * FROM ".$tabl." WHERE kodrasdel=?"; // LIKE '%'"
        //$stmt = $connect->prepare($sql);
        //$stmt->execute(array($record));
        if($members){
            return 1;
        } else{return 0;}
    }

    private function delCur($record,$pole){
        $connect=$this->db();
        $sql="DELETE FROM ".$this->nametabl()." WHERE ".$pole."=?";// LIKE '%'"
        $stmt = $connect->prepare($sql);
        $stmt->execute(array($record));
    }

    private function clearPict($tabl,$record,$pole,$put){ //удалить из табл картинок и картинки
        $connect=$this->db();
        $sql="SELECT * FROM ".$tabl." WHERE ".$pole."=?"; // LIKE '%'"
        $stmt = $connect->prepare($sql);
       // debug_to_console($record);
        foreach ($record as $key=>$value) {
            $stmt->execute(array($value));
            if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
             //  debug_to_console($sms);
                foreach ($sms as $row){
                    $namep=$put.$row["name"];$namesm=$put.$row["name_small"];
                   // debug_to_console($namep);
                    unlink($namep);	unlink($namesm);
                }
                $sql2="DELETE FROM ".$tabl." WHERE ".$pole."=?";// LIKE '%'"
                $stmt2 = $connect->prepare($sql2);
                $stmt2->execute(array($value));
            }
        }
    }

    private function del_sort($zapros) { // сортировка после удаления
        $sorty=array();
        $dbl=$this->db();
        $tbl=$this->nametabl();
        $sql="SELECT  kod FROM ".$tbl.$zapros."  ORDER BY sort;"; //WHERE kodmenu=? and kodrasdel=?
        $stmt = $dbl->prepare($sql);
        $stmt->execute(); //array($this->kodmenu,$this->kodrasdel)
        $k=0;
        if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
            foreach($sms as $row){
                $sorty[$k]["kod"]=$row["kod"];
                $k++;
            }
            //debug_to_console($sorty);
        }

        $sql="UPDATE ".$tbl." SET sort =? WHERE kod = ?; "; // LIKE '%'"
        $stmt = $dbl->prepare($sql);
        for($i=0;$i<count($sorty);$i++) {
            $k=$i+1;

            //$sql="UPDATE ".$this->tblimg." SET sort =".$k." WHERE kod = " . $sorty[$i]["kod"] . "; "; // LIKE '%'"

            $stmt->execute(array($k,$sorty[$i]["kod"]));

        }
        $this->kolpict=count($stmt);
    }






























	/*public function checkDelRecord($record){ // проверка можно ли удалять
	$connect=$this->db();
		$tbl=$this->nametabl(); $result=''; $resultpict='';
		include('var_alt.php');
		$cur_uroven=$massUroven[$tbl];
		foreach($massUroven as $key=>$value){
			if($tbl=='mainmenu'){$tmp='kodmenu';} else{$tmp='kodrasdel';}
			if($value > $cur_uroven ){$result.=str_replace('[ZAM]',$massTablAlias[$key],$this->checkTableAval($key,$record,$tmp));}
			if($value == $cur_uroven and $value<2){$result.=str_replace('[ZAM]',$massTablAlias[$key],$this->checkTableAval($key,$record,$tmp));}
		}
		$cur_pictur=$picturTbl[$tbl];
		$put=$picturKat[$tbl];
		if(strlen($result)==0){
			if (strlen($cur_pictur)>0) {
				$this->delPicturAval($cur_pictur,$record,'kodrasdel',$put);
				//$resultpict=str_replace('[ZAM]',$massTablAlias[$cur_pictur],$this->checkTableAval($cur_pictur,$record,$tmp));
			}	
			$sql="DELETE FROM ".$tbl." WHERE kod='".$record."';"; // LIKE '%'"
			$stmt = $connect->prepare($sql);
			$stmt->execute();	
			$this->del_sort();
		}
		
		
	return $result;
	}
	
	private function checkTableAval($tbl,$record,$tmp){ // есть ли данные таблицы в разделе или меню
		$connect=$this->db(); $result='';
		$sql ="SELECT * FROM ". $tbl." WHERE ".$tmp."=?";
		$stmt = $connect->prepare($sql);
		$stmt->execute(array($record));	
		if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
			$result='Внутри есть данные [ZAM]. ';	
		}
	//	debug_to_console($sql);
		return $result;
	}
	
	private function delPicturAval($tbl,$record,$tmp,$put){ // удаление картинок привязанных к пункту
		$connect=$this->db(); $result='';
		$sql="SELECT * FROM ".$tbl." WHERE ".$tmp."=?"; // LIKE '%'" 
		$stmt = $connect->prepare($sql);
		$stmt->execute(array($record));
		if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
			foreach ($sms as $row){
				$namep=$put.$row["name"];$namesm=$put.$row["name_small"];
				//debug_to_console($namep);
				unlink($namep);	unlink($namesm);
			}
			$sql="DELETE FROM ".$tbl." WHERE ".$tmp."=?";// LIKE '%'"
				$stmt = $connect->prepare($sql);
				$stmt->execute(array($record));	
		}	
		
	}
	
	
	private function del_sort() { // сортировка после удаления
		$sorty=array();
		$dbl=$this->db();
		$tbl=$this->nametabl();	
		$sql="SELECT  kod FROM ".$tbl." WHERE kodmenu=? and kodrasdel=? ORDER BY sort;";
		$stmt = $dbl->prepare($sql);
		$stmt->execute(array($this->kodmenu,$this->kodrasdel));
		$k=0;
		if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
			foreach($sms as $row){
			$sorty[$k]["kod"]=$row["kod"];
			$k++;
			}
			//debug_to_console($sorty);
		}
		
		$sql="UPDATE ".$tbl." SET sort =? WHERE kod = ?; "; // LIKE '%'" 
		$stmt = $dbl->prepare($sql);
		for($i=0;$i<count($sorty);$i++) {
			$k=$i+1;
			
			//$sql="UPDATE ".$this->tblimg." SET sort =".$k." WHERE kod = " . $sorty[$i]["kod"] . "; "; // LIKE '%'" 	
			
			$stmt->execute(array($k,$sorty[$i]["kod"]));
			
		}
		$this->kolpict=count($stmt);
	}	*/
}
?>