<?
class WindowChangePictur extends WindowInputPictur{ // изменение картинок
	private $id;
	private $param;
	private $txt;
	private $coords;
	private $kodrasdel;
	public $kolpict;
	private $Wnewminiimg; private $Hnewminiimg;
	
	public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }
	
	public function initChange($id,$param,$txt,$kodrasdel,$coords) { // инициализация данных
		$this->id=str_replace('nom','',$id);
		$this->param=$param;
		$this->txt=$txt;
		$this->kodrasdel=$kodrasdel;
		$this->coords=$coords;
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

        if($this->param==3) {    // сделать новон превью
            $sql="SELECT * FROM ".$this->tblimg." WHERE kod = ?; "; // LIKE '%'"
            $stmt = $dbl->prepare($sql);
            $stmt->execute(array($this->id));
            if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $this->recieveMinsize();
                $src = $this->putimg.$sms[0]['name'];
                $this->cutPreview($src);
            }
		}
        if($this->param==4) {    // повернуть картинку
            $sql="SELECT * FROM ".$this->tblimg." WHERE kod = ?; "; // LIKE '%'"
            $stmt = $dbl->prepare($sql);
            $stmt->execute(array($this->id));
            if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $src = $this->putimg.$sms[0]['name'];
                $this->rotatePict($src);
            }
		}
	}
    private function rotatePict($src){
        $type=getimagesize($src);
        $type=str_replace('image/','',$type['mime']);
        switch ($type){
            case 'png':$img_r = imagecreatefrompng($src); break;
            case 'gif':$img_r = imageCreateFromGif($src); break;
            case 'jpeg':;
            case 'jpg':$img_r = imagecreatefromjpeg($src); break;
            default: break;
        }
        switch ($this->coords[5]){
            case'-1':$rotate = imagerotate($img_r, 90, 0); break;
            case'1':$rotate = imagerotate($img_r, 270, 0); break;
        }
        switch ($type){
            case 'jpeg':;
            case 'jpg':$img_r = imagejpeg($rotate,$src ); break;
            case 'png':$img_r = imagepng($rotate,$src ); break;
            case 'gif':$img_r = imagegif($rotate,$src ); break;
        }
        imagedestroy($rotate);
    }

	private function cutPreview($src){
        $targ_w =  $this->Wnewminiimg ;
        $targ_h = $this->Hnewminiimg;
        $jpeg_quality = 100;
       // $info = new SplFileInfo($src);
       // $type=$info->getExtension();
        $type=getimagesize($src);
        // debug_to_console($type);
        $type=str_replace('image/','',$type['mime']);

        switch ($type){
            case 'png':$img_r = imagecreatefrompng($src); break;
            case 'gif':$img_r = imageCreateFromGif($src); break;
            case 'jpeg':;
            case 'jpg':$img_r = imagecreatefromjpeg($src); break;
            default: break;
        }
        $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
        $x_cor=(int)($this->coords[0]/$this->coords[4]);
        $y_cor=(int)($this->coords[1]/$this->coords[4]);
        $w_cor=(int)($this->coords[2]/$this->coords[4]);
        $h_cor=(int)($this->coords[3]/$this->coords[4]);
        imagecopyresampled($dst_r,$img_r,0,0,$x_cor,$y_cor,
            $targ_w,$targ_h,$w_cor,$h_cor);
       // $type=getimagesize($src);
        $newname=str_replace('big','small',$src);
        switch ($type){
            case 'jpeg':;
            case 'jpg':$img_r = imagejpeg($dst_r,$newname,$jpeg_quality ); break;
            case 'png':$img_r = imagepng($dst_r,$newname ); break;
            case 'gif':$img_r = imagegif($dst_r,$newname,$jpeg_quality ); break;
        }
        imagedestroy($dst_r);
       // imagedestroy($img_r);
        //debug_to_console($type);


    }

    private function recieveMinsize(){
        $dbl=$this->db();
        $hprev=''; $wprev=''; $horig=''; $worig='';
        $sql="SELECT  * FROM sets ;";
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
            //debug_to_console($sms);
            foreach($sms as $row){
                switch($row['parametr']){
                    case 'heightpicturpreviewmax': $hprev=intval($row['value']);break;
                    case 'weightpicturpreviewmax': $wprev=intval($row['value']);break;
                    case 'heightpicturmax': $horig=intval($row['value']);break;
                    case 'weightpicturmax': $worig=intval($row['value']);break;
                }
            }
        }
       // $this->Wnewimg = ($worig ? $worig : 700);//Ширина нового изображение (дефолт 700px)
      //  $this->Hnewimg = ($horig ? $horig : 700);//Высота нового изображение (дефолт 700px)
        $this->Wnewminiimg = ($wprev ? $wprev : 150);//Ширина нового мини изображение (дефолт 150px)
        $this->Hnewminiimg = ($hprev ? $hprev : 150);//Высота нового мини изображение (дефолт 150px)
      //  $this->max_size_img = 10;//Максимально допустимый размер изображения (дефолт 10 мб)
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