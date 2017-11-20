<?
class WindowSavePictur extends WindowInputPictur{ // сохранение картинок
	private $Wnewimg ; private $Hnewimg ;
	private $Wnewminiimg ; private $Hnewminiimg ;
	private $max_size_img ;
	public $typictur;
	public $namepict;
			
	public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }
	
	public function defaultValue(){ // задать параметры по умолчанию
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
        $this->Wnewimg = ($worig ? $worig : 700);//Ширина нового изображение (дефолт 700px)
        $this->Hnewimg = ($horig ? $horig : 700);//Высота нового изображение (дефолт 700px)
        $this->Wnewminiimg = ($wprev ? $wprev : 150);//Ширина нового мини изображение (дефолт 150px)
        $this->Hnewminiimg = ($hprev ? $hprev : 150);//Высота нового мини изображение (дефолт 150px)
        $this->max_size_img = 10;//Максимально допустимый размер изображения (дефолт 10 мб)
        // echo $this->Wnewminiimg.' '. $this->Wnewimg;
		/*$this->Wnewimg = 700;//Ширина нового изображение (дефолт 700px)
		$this->Hnewimg = 700;//Высота нового изображение (дефолт 700px)
		$this->Wnewminiimg = 150;//Ширина нового мини изображение (дефолт 100px)
		$this->Hnewminiimg = 150;//Высота нового мини изображение (дефолт 100px)
		$this->max_size_img = 10;//Максимально допустимый размер изображения (дефолт 10 мб)*/
	}
	
	public function testPictur($galeryfile,$nomer,$file){ // проверка картинки
		$mass['kod']=1; $mass['txt']='';
		$type = $galeryfile['type'];//определяем тип изображения, а именно jpg он или bmp
		$kb = $galeryfile['size'];//узнаем вес изображения в байтах
		switch ($type) {
   			 case "image/jpeg": $t=1; break;
   			 case "image/gif": $t=2;break;
    		 case "image/png": $t=3;break;
			 default: $t=0;	
			}
		if($t==0){$mass['kod']=0;$mass['txt'].='Неверный тип изображения';} else{$this->typictur=$t;}	
		 if($kb >= $this->max_size_img*1024*1024 )//максимальный вес изображение не более 10485760 байт ( 10 мб )
    	{ $mass['kod']=0; $mass['text'].="Большой размер файла."; }
		 $nom=$this->makename($nomer,$file,$galeryfile['name']); // получить последний номер сортировки
		 if(strlen($nom)==0){ $mass['kod']=0; $mass['text'].="Проблема с именем файла."; }
	return $mass;	 
	}
	
	private function makename($nomer,$file,$name){ // получить последний номер сортировки
		$this->namepict='';
		$dbl=$this->db();
		$tbl=$this->nametabl();
		$menu=$this->nomermenu($nomer);
		$tblimg=$this->tblimg();
		$sql="SELECT  COUNT(*) as count FROM ".$tblimg." WHERE kodrasdel=".$nomer.";"; 
		$stmt = $dbl->prepare($sql);
		$stmt->execute(); 
		if($sms=$stmt->fetch(PDO::FETCH_ASSOC)) { $tmp=$sms['count']+1;} else{$tmp=1;}
        $namefile=mb_substr($name,0,40);
        $namefile=$this->translit($namefile);
			if(strlen($file) && preg_match('/^[A-Z0-9_-]+$/i',$file)) {$file.='-'.date('ymdhi');}else {$file=$namefile.'-'.date('ymdhi');}
		$this->namepict=substr($menu.'-'.$nomer.'-'.$tmp.'-'.$file,0,240);
		return  $this->namepict;
	}
	
	private function nomermenu($nomer){ // получить номер меню
		$dbl=$this->db();
		$tbl=$this->nametabl();
		$sql="SELECT  * FROM ".$tbl." WHERE kod=".$nomer.";";
		$stmt = $dbl->prepare($sql);
		$stmt->execute();
		if($sms=$stmt->fetch(PDO::FETCH_ASSOC)) { $tmp=$sms['kodmenu'];} else{$tmp=0;}
		return $tmp;
	}
	
	public function enginePictur($galeryfile,$nomer,$note){ // обработка картинки
		$name_small = $this->namepict."_small";//раскидываем в переменные новое имя...
		 $name_big = $this->namepict."_big";;//...для изображения
		 $size = getimagesize($galeryfile['tmp_name']);//узнаем ширину и высоту загружаемого изображения
			if($size[0] <= $this->Wnewimg AND $size[1] <= $this->Hnewimg)//Если фото не надо уменьшать в размере
				{ 
				$this->newIMG($galeryfile['tmp_name'],$size[0],$size[1],$size[0],$size[1],$name_big,1,0,0,$this->typictur);}
				//то запускаем функцию которая занесет в нужную папку наше изображение
				else//Если надо (фото больше чем 700х700 px)
				{
					  if($size[0] < $size[1])//Если вертикальное изображение (пример 800 на 1000 пикселей)
           				 {
               			 $h_rb = $size[1]/$this->Hnewimg;//высоту делим на максимальную высоту. По умолчанию на 700, получаем 1,4285
               			 $wb = $size[0]/$h_rb;//ширину делим на 1,4285, получаем 560,028
               			 $hb = $this->Hnewimg;//заносим максимальную высоту изображения
                			//на выходе мы получаем ширину изображения 560px высоту 700px
           				 }
           				 else//Если горизонтальное изображение (пример 1000 на 800 пикселей)
           				 {
               			 $w_rb = $size[0]/$this->Wnewimg;//Делим ширину на максимальную ширину. Получаем 1,4285
                		 $hb = $size[1]/$w_rb;//делим высоту на 1,4285, получаем 560,028
                		 $wb = $this->Wnewimg;//заносим максимальную ширину
                			//на выходе мы получаем изображение шириной 700px и высотой 560px
           				 }
           			 if($size[0] == $size[1])//Квадратное изображение
           				 {
               			 $wb = $this->Wnewimg;//заносим максимальную ширину
               			 $hb = $this->Hnewimg;//заносим максимальную высоту
           				 }
            
           	 $this->newIMG($galeryfile['tmp_name'],$wb,$hb,$size[0],$size[1],$name_big,1,0,0,$this->typictur);
			//вызываем функцию которая занесет нужное нам изображение в нужную папку 
       		 }
		 //Создание мини изображения
      		  if($size[0] > $size[1])//Горизонтальное изображение
       			 {
           			 //то определяем на сколько необходимо обрезать изображение
           		 $obrez_w = $size[0] - $size[1];//определяем на сколько ширина больше высоты
           		 $obrez_h = 0;//так как высота меньше ширины, то ее мы не обрезаем
       			 }
       			 else//Вертикальное изображение
       			 {
           		 //то определяем на сколько необходимо обрезать изображение
           		 $obrez_h = $size[1] - $size[0];//определяем на сколько высота больше ширины
           		 $obrez_w = 0;//так как ширина меньше высоты, то обрезаем ее на ноль!
       		 }
        	if($size[0] == $size[1])//Квадратное
       			 {
           		 //то определяем на сколько необходимо обрезать изображение
           		 $obrez_h = 0;//но так как высота и ширина равны
           		 $obrez_w = 0;//обрезать их не нужно
       			 }
        $this->newIMG($galeryfile['tmp_name'],$this->Wnewminiimg,$this->Hnewminiimg,$size[0],$size[1],$name_small,0,$obrez_w,$obrez_h,$this->typictur);
		//запускаем функцию, которая создаст нам мини изображение в папке galery/mini/
        // конец Создание мини изображения
		$kol=$this->savepict($name_big, $name_small, $nomer, $note,$this->typictur);
	
	return $kol;		
	}
	
	private function newIMG($n_up,$w_new,$h_new,$w_up,$h_up,$n_new,$b_or_m,$obrez_w,$obrez_h,$t)

		//Функция cжатия изображений до нужного разрешения
		//$n_up - Загружаемый файл
		//$w_new - Ширина нового файла
		//$h_new - Высота нового файла
		//$w_up - Ширина загружаемого файла
		//$h_up - Высота загружаемого файла
		//$n_new - Имя нового файла
		//$b_or_m - Пред просмотр (0) или основной файл (1)
		//$obrez_h - Координаты для обрезания исходного изображение (по дефолту 0)
		//$obrez_w - Координаты для обрезания исходного изображение (по дефолту 0)
	{
	//$putimg="../../img/imgcat/";

	switch ($t) {  //создаем новое изображение из загруженного файла
    case 1: $instant = imagecreatefromjpeg($n_up);  //"image/jpeg"
        break;
    case 2: $instant = imagecreatefromgif($n_up); //"image/gif"
        break;
    case 3: $instant = imagecreatefrompng($n_up); //"image/png"
        break;
	}
	
    $new_img = imagecreatetruecolor($w_new, $h_new);//создаем пустое изображение нужной высоты и ширины
    if($b_or_m == 0)//Если изображение является пред просмотром
    {
        $h_up -= $obrez_h;//уменьшаем высоту загруженного файла на обрезаемое количество пикселей
        $obrez_h = 0;//заносим в высоту 0
        $w_up -= $obrez_w;//уменьшаем ширину на обрезаемое количество пикселей
        $obrez_w /= 2;//делим обрезаемое количество пикселей на 2
    }
    imagecopyresampled($new_img,$instant,0,0,$obrez_w,$obrez_h,$w_new,$h_new,$w_up,$h_up);//создаем изображение
	
	switch ($t) {
   case 1:  if($b_or_m == 0)imagejpeg($new_img, $this->putimg.$n_new.".jpg", 100);//записываем полученное изображение в папку /galery/mini/
    		if($b_or_m == 1)imagejpeg($new_img, $this->putimg.$n_new.".jpg", 100);//записываем полученное изображение в папку /galery/big/
			break;
	case 2: if($b_or_m == 0)imagegif($new_img, $this->putimg.$n_new.".gif", 100);//записываем полученное изображение в папку /galery/mini/
    		if($b_or_m == 1)imagegif($new_img, $this->putimg.$n_new.".gif", 100);//записываем полученное изображение в папку /galery/big/
			break;
	case 3: if($b_or_m == 0)imagepng($new_img, $this->putimg.$n_new.".png");//записываем полученное изображение в папку /galery/mini/
    		if($b_or_m == 1)imagepng($new_img, $this->putimg.$n_new.".png");//записываем полученное изображение в папку /galery/big/
			break;
	}
	imagedestroy($new_img);//уничтожаем заготовки изображений
    imagedestroy($instant);//уничтожаем заготовки изображений
	}
	
	private function savepict($namebig, $namesmall, $nomer, $note, $t){ // запись в каталог
		$dbl=$this->db();
		$tbl=$this->tblimg;
		$sql="SELECT sort, kod FROM ".$tbl." WHERE kodrasdel=".$nomer." ORDER BY sort DESC ;";
		$stmt = $dbl->prepare($sql);
		$stmt->execute();
		if($sms=$stmt->fetch(PDO::FETCH_ASSOC)) {$maxsort=$sms["sort"];}else {$maxsort=0;}
		$maxsort++;
		switch ($t) {
			case 1: $namebig.=".jpg"; $namesmall.=".jpg"; break;
			case 2: $namebig.=".gif"; $namesmall.=".gif"; break;
			case 3: $namebig.=".png"; $namesmall.=".png"; break;
		}
		$sql="INSERT INTO ".$tbl." ( name, name_small, kodrasdel, sort, note) VALUES(?,?,?, ?, ?); ";
		$stmt = $dbl->prepare($sql);
		//debug_to_console(array($namebig,$namesmall,$nomer,$maxsort,$note));
		$stmt->execute(array($namebig,$namesmall,$nomer,$maxsort,$note));
		
		$sql="SELECT COUNT(*) as count FROM ".$tbl." WHERE kodrasdel=".$nomer.";";
		$stmt = $dbl->prepare($sql);
		$stmt->execute();
		if($sms=$stmt->fetch(PDO::FETCH_ASSOC)) { $kolrec=$sms['count'];} else{$kolrec=0;}
		
		return $kolrec;
	}
    private function translit($s) {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }
}
?>