<?php
class createMenu{
    private $db;
    private $nametabl;
    public $mainmenu;
    public $prefix;
    public $kodrasdel; public $kodmenu;
    public $urlrasdel; public $urlmenu;
    public $itogname;

    public function __construct($nmtbl,$dbh) {
        // $this->sql ="SELECT * FROM ".$nametabl."";
        $this->db=$dbh; $this->nametabl=$nmtbl;
        $this->prefix='http://'.$_SERVER["HTTP_HOST"].'/';
    }
    public function initRasdMen($kodrasdel,$kodmenu,$itogname){
        $this->kodmenu=$kodmenu; $this->itogname=$itogname;
        $this->kodrasdel=$kodrasdel; $this->urlmenu='';$this->urlrasdel='';
        if($tmp=$this->urlUrovnya('mainmenu','kod',$kodmenu)) {$this->urlmenu=$tmp;}
        if($tmp=$this->urlUrovnya('rasdel','kod',$kodrasdel)) {$this->urlrasdel=$tmp;}
    }
    private function urlUrovnya($tbl,$pole,$val){
        $dbl=$this->db;
        $sql = "SELECT * FROM ".$tbl. ' Where '.$pole.'='.$val;
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $temp=$this->CMP($sms[0],'name');
        }
        return $temp;
    }

    public function massivMenu($where){  //make array main
        $dbl=$this->db; $mass=array();
        $sql = "SELECT * FROM ".$this->nametabl." ".$where."  ORDER by sort";
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            //debug_to_console($sms);
            foreach ($sms as $row) {
                $mass[]=array('kod'=>$row['kod'], 'name'=>$row['name'],'nameurl'=>$this->CMP($row,'name'),'titlepage'=>$row['titlepage'],'rol'=>$row['rol']);
            }
        }
        $this->mainmenu=$mass;
    }
    public function massivRasdel($where,$pictur){  //make array main
        $dbl=$this->db; $mass=array();
        $sql = "SELECT * FROM ".$this->nametabl." ".$where."  ORDER by sort";
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            //debug_to_console($sms);
            foreach ($sms as $row) { $url='';
                if($pictur){$url=$this->pictUrl($row['kod']);}
                $mass[]=array('kod'=>$row['kod'], 'name'=>$row['name'],'nameurl'=>$this->CMP($row,'name'),'titlepage'=>$row['titlepage'],'rol'=>$row['rol'],'pictur'=>$url,'kodrasdel'=>$this->kodrasdel,'kodmenu'=>$this->kodmenu);
            }
        }
        $this->mainmenu=$mass;
    }
    private function pictUrl($kod){ // получить урл картинки полный переписать на один запуск сразу в массив все картинки с кодраздел-кодменю
        include('../adm/mod/class/var_alt.php');
        $dbl=$this->db;
        $dir=str_replace('../..','', $picturKat[$this->nametabl]);
        $tblimg=$picturTbl[$this->nametabl];
        $where=' Where kodrasdel='.$kod.'';
        $sql = "SELECT * FROM ".$tblimg." ".$where."  ORDER by sort";
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $tmpurl=$dir.$sms[0]['name_small'];
        } else {$tmpurl='/img/nopict.jpg';}
        return $tmpurl;
    }

    private function CMP($row,$name){ // nameurl translation
        if(strlen($row['nameurl'])){$nameurl=($row['nameurl']); }
        else{$nameurl=translit($row[$name]); }
        //$temp=array('nameurl'=>$nameurl,'rol'=>$row['rol'],'name'=>$row['name'],'kodmenu'=>$kodmenu,'rasdel'=>$row['kodrasdel'],'table'=>$nmtbl,'kod'=>$row['kod']);
       // debug_to_console($sms);
        return $nameurl;
    }

    private function translit($s) {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s,'utf-8') : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        //  echo $s; //echo iconv('cp1251','utf-8',$s);
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }
}

?>