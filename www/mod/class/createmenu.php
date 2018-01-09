<?php
class createMenu{
    private $db;
    private $nametabl;
    public $mainmenu; public $allrasdel;
    public $prefix;
    public $kodrasdel; public $kodmenu;
    public $namerasdel;
    public $urlrasdel; public $urlmenu;
    public $itogname;
    private $typrasdel=array('3'=>'articles','5'=>'playlist','6'=>'galery','0'=>'articles');
    private $kolOnPage=2;

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
        $this->allrasdel=$mass;
    }
    private function pictUrl($kod){ // получить урл картинки полный переписать на один запуск сразу в массив все картинки с кодраздел-кодменю
       // $put = $this->prefix.'adm/mod/class/var_alt.php';
     //   debug_to_console($put);
       // include ($put);

        if(file_exists('../adm/mod/class/var_alt.php')) {$put='../adm/mod/class/var_alt.php';}
        else{$put='./adm/mod/class/var_alt.php';}
       //include'../adm/mod/class/var_alt.php';
        include ($put);
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
        if(strlen($row['nameurl'])){$nameurl=$this->translit($row['nameurl']); }
        else{$nameurl=$this->translit($row[$name]); }
        //$temp=array('nameurl'=>$nameurl,'rol'=>$row['rol'],'name'=>$row['name'],'kodmenu'=>$kodmenu,'rasdel'=>$row['kodrasdel'],'table'=>$nmtbl,'kod'=>$row['kod']);
       // debug_to_console($sms);
        return $nameurl;
    }

    public function initRasdMenOnly($kodrasdel,$kodmenu,$itogname)
    {
        $this->kodmenu = $kodmenu;
        $this->itogname = $itogname;
        $this->kodrasdel = $kodrasdel;
    }
    private function typeRasdel(){ // тип раздела
        $tmp=0;
        if($this->kodrasdel) {
            $dbl = $this->db;
            $where=' WHERE kod='.$this->kodrasdel. ' and kodmenu='.$this->kodmenu;
            $sql = "SELECT * FROM " . 'rasdel' . " " . $where . "  ORDER by sort";
            $stmt = $dbl->prepare($sql);
            $stmt->execute();
            if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {$tmp=$sms[0]['rol'];
                $this->namerasdel=$sms[0]['name'];
            }
        }
        return $this->typrasdel[$tmp];
    }

        public function currentArticle($tbl,$where,$itogname){

        $dbl=$this->db;
        $sql = "SELECT * FROM ".$tbl." ".$where."  ORDER by sort";
            //debug_to_console($sql);
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        $temp=''; $key='';
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {

            switch($this->typeRasdel()){
                case 'articles':
                    if(count($sms)>1){ $temp=$this->makeArticleAll($sms,$itogname,'news','description');} //обработать комплект статей
                    if(count($sms)==1) { //обработать одну статей
                        if($redirect=$sms[0]['$redirect']) {} //обработать редирект
                        $temp=$this->makeArticle($sms[0],$itogname);
                    }
                    if(count($sms)==0){} //обработать нет статей
                    break;

            }
        }
         return $temp;
    }

    private function makeArticle($sms,$itogname){
        $tmp=htmlspecialchars_decode($sms['post']); $book='';
        $key=htmlspecialchars_decode($sms['keywords']);
        $pieces = explode("[_page]", $tmp);
       // debug_to_console($pieces);
        for($i=0;$i<count($pieces);$i++) {
            if($i==0){$styl='current ';} else {$styl='';}
            $book.='<div id="page_'.($i+1).'" class="secstr  '.$styl.'" name="'.$itogname.'">'.
                '<img class="blokpage" src="/img/blokpage.jpg">'.
                '<div class="txt_block_head">'.$sms['name'].'</div>'.
                '<div class="txt_block">'.  $pieces[$i].'</div></div>';
        }
       // $book='<div id="mainpages" class="mainpages" name="'.'">'.$book.'</div>';
        return array($book,$key);
    }
    private function makeArticleAll($sms,$itogname,$tbl,$pole){
        $tmp=''; $masstr=array(); $masspage=array(); $key='';
        if(file_exists('../adm/mod/class/var_alt.php')) {$put='../adm/mod/class/var_alt.php';}
        else{$put='./adm/mod/class/var_alt.php';}
        include ($put);
        $this->massivMenu('  ');
        $this->massivRasdel('',0);
        $dbl=$this->db; $tblimg=$picturTbl[$tbl];
        $putimg=str_replace('../..','',$picturKat[$tbl]);
        $where=' WHERE kodrasdel=? ';
        $sql = "SELECT * FROM ".$tblimg." ".$where."  ORDER by sort";
        $stmt = $dbl->prepare($sql);
        foreach ($sms as $row){
            $stmt->execute(array($row['kod']));
            if ($sms2 = $stmt->fetchAll(PDO::FETCH_ASSOC)) {$namepict=$sms2[0]['name_small'];}
            else {$namepict='/img/nopict.jpg';}
            $id=$row['kod'].'_'.$row['kodmenu'].'_'.$row['kodrasdel'];

            $url=$this->makeUrlArt($id).'/'.$this->CMP($row,'name');
            $tmp='<tr class="tablnews" name="'.$id.'">';
            $tmp.='<td><a class="linkarticle" href="'.$url.'"><img src="'.$putimg.$namepict.'"></a></td>';
            $tmp.='<td><div class="description">'.$row[$pole].'<div class="dalee"><a class="linkarticle" href="'.$url.'">Далее...</a></div></div></td></tr>';
            $masstr[]=$tmp;
        } //debug_to_console($masstr);
            $kolPage=$this->kolOnPage; $cont=0; $tmp='';
        foreach($masstr as $row){
            $tmp.=$row; $cont=$cont+1;
            if($cont==$kolPage) {$cont=0; $masspage[]='<table>'.$tmp.'</table>'; $tmp='';}
        }
        if(strlen($tmp)>0) $masspage[]='<table>'.$tmp.'</table>';
        $book='';// debug_to_console(count($masspage));
        for($i=0;$i<count($masspage);$i++) {
            if($i==0){$styl='current ';} else {$styl='';}
            $book.='<div id="page_'.($i+1).'" class="secstr  '.$styl.'" name="'.$itogname.'">'.
                '<img class="blokpage" src="/img/blokpage.jpg">'.
                '<div class="txt_block_head">'.$this->namerasdel.'</div>'.
                '<div class="txt_block">'.  $masspage[$i].'</div></div>';
        }
        return array($book,$key);
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

    public function getKodes($mass,$tbl){
        $menu=0; $rasd=0; $article=0;
        if(count($mass)>1) { $menu=$this->findMenu($mass[1],$tbl,0,0);}
        if(count($mass)>2) { $rasd=$this->findMenu($mass[2],'rasdel',$menu,0);}

        return array($menu,$rasd,$article);
    }
    private function findMenu($namemenu,$tbl,$kodmenu,$kodrasdel){
        $dbl=$this->db; $kod=0;
        $sql = "SELECT * FROM ".$tbl." WHERE kodmenu=".$kodmenu." AND kodrasdel=".$kodrasdel." ORDER by sort";
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($sms as $row) {
                $tmp=$this->CMP($row,'name');
                if ($tmp==$namemenu) {$kod=$row['kod'];}
            }

        }
        return $kod;
    }

    private function makeUrlArt($id){ // делаем ссылку по кодам меню+раздел
        $url='';
        $mass=explode('_',$id);
       // debug_to_console($this->urlmenu);
        foreach ($this->mainmenu as $row) { if($row['kod']==$mass[1]) $url.='/'.$row['nameurl']; }
        foreach ($this->allrasdel as $row) { if($row['kod']==$mass[2]) $url.='/'.$row['nameurl']; }
        //debug_to_console($mass);
        return $url;
    }

}

?>