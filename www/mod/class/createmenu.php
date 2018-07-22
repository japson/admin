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
    private $typarticle=array('1'=>'articles','3'=>'playlist','2'=>'galery');
    private $kolOnPage=4; private $PictOnPage=9; private $limpictonrow=3; private $limalbumpage=2;
    public $cofmen=''; public $cofrasd='';
    public $opengraph=array();
    public $masspencil=array();
    public $kodarticle;
    public $prevart; public $nextart; public $listart;

    public function __construct($nmtbl,$dbh) {
        // $this->sql ="SELECT * FROM ".$nametabl."";
        $this->db=$dbh; $this->nametabl=$nmtbl;
        $this->prefix='https://'.$_SERVER["HTTP_HOST"].'/';

    }
    public function renameTabl($new){$this->nametabl=$new;}

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
        $dbl=$this->db; $mass=array();$tbl='mainmenu';
        $sql = "SELECT * FROM ".$tbl." ".$where."  ORDER by sort";
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
           // debug_to_console($sms);
            foreach ($sms as $row) { $url='';
                if($pictur){$url=$this->pictUrl($row['kod']);}else {$url='/img_n/nopict.jpg';}
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
        } else {$tmpurl='/img_n/nopict.jpg';}
        return $tmpurl;
    }

    public function CMP($row,$name){ // nameurl translation
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
       // debug_to_console($tmp);
        return $this->typrasdel[$tmp];
    }

        public function currentArticle($tbl,$where,$itogname){

        $dbl=$this->db;
        $sql = "SELECT * FROM ".$tbl." ".$where."  ORDER by sort";
          // debug_to_console($sql);
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        $temp=''; $key='';
           // debug_to_console($this->typeRasdel());
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $this->masspencil=$this->makePencil($tbl,$sms[0]);
            switch($this->typeRasdel()){
                case 'playlist':;
                    $temp=$this->makePlayList(0);
                    $temp=$this->makehtmlList($temp,$itogname);
                   // debug_to_console($temp);
                break;
                case 'articles': //debug_to_console($this->typeRasdel());
                    if(count($sms)>1){ $temp=$this->makeArticleAll($sms,$itogname,'news','description');} //обработать комплект статей
                    if(count($sms)==1) { //обработать одну статей
                        if($redirect=$sms[0]['redirect']) {$temp=$this->redirect($sms[0],0);}
                        //todo:обработать редирект
                        else{$temp=$this->selectTypArticle($sms[0],$itogname);}
                    }
                    if(count($sms)==0){$temp=array();} //обработать нет статей
                    break;

            }
        }
         return $temp;
    }
    private function makePencil($tbl,$record){ // то что на карандаше
        $dbl=$this->db; $prev=0;$next=0; $pagerasdel='';
        $sort=$record['sort']; $mass=array();
        $where=' WHERE kodrasdel='.$this->kodrasdel.' and kodmenu='.$this->kodmenu.' and vyvod=1';
        $sql = "SELECT kod, sort, name, nameurl,redirect FROM ".$tbl." ".$where."  ORDER by sort";
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                foreach($sms as $value){$mass[]=array($value['kod'],$value['name'],$value['nameurl'],$value['redirect']);}
                for($i=0;$i<count($mass);$i++){
                    if($mass[$i][0]==$this->kodarticle) {
                        $pagerasdel=(integer)($i/$this->kolOnPage);
                        if($i==(count($mass)-1) && count($mass)==1){$prev=$next=$mass[0];}
                        else{
                            switch($i){
                                case 0: $prev=$mass[count($mass)-1];$next=$mass[$i+1]; break;
                                case count($mass)-1: $next=$mass[0];$prev=$mass[$i-1];break;
                                default: $next=$mass[$i+1];$prev=$mass[$i-1];break;
                            }
                        }
                    }
                }
                $temptbl=$this->nametabl;
            $this->nametabl='mainmenu'; $this->massivMenu('  ');
            $this->nametabl='rasdel';$this->massivRasdel('',0);
            $this->nametabl=$temptbl;

            $prevmass=$this->processPenculRed($prev);$prevredir=array();
            $prevtrue=$prevmass[0]; $makeurl=$prevtrue;
            if(count($prevmass)>1){$prevredir=$prevmass[1];$makeurl=$prevredir;}
           // debug_to_console($prevmass);
            $this->prevart=array($prevtrue[0]);
           // $this->prevart=array($prev[0].'_'.$this->kodmenu.'_'.$this->kodrasdel);
            $menbegin=$this->makeUrlArt($makeurl[0]);
            $this->prevart[]=$menbegin.'/'.$this->CMP(array('name'=>$makeurl[1],'nameurl'=>$makeurl[2]),'name');
            if(count($prevredir)){$this->prevart[]=$prevredir[0];}else{$this->prevart[]='';}

            $nextmass=$this->processPenculRed($next);$nextredir=array();
            $nextrue=$nextmass[0]; $makeurl=$nextrue;
            if(count($nextmass)>1){$nextredir=$nextmass[1];$makeurl=$nextredir;}
            $this->nextart=array($nextrue[0]);
           // $this->nextart=array($next[0].'_'.$this->kodmenu.'_'.$this->kodrasdel);
            $menbegin=$this->makeUrlArt($makeurl[0]);
            $this->nextart[]=$menbegin.'/'.$this->CMP(array('name'=>$makeurl[1],'nameurl'=>$makeurl[2]),'name');
            if(count($nextredir)){$this->nextart[]=$nextredir[0];}else{$this->nextart[]='';}

            $this->listart=array($this->kodrasdel.'_'.$this->kodmenu.'_0',$menbegin.'/#'.($pagerasdel+1));
        }
       // $this->massivMenu('  ');
       // $this->massivRasdel('',0);
    }
    private function processPenculRed($page){ $tmp=array();
       // debug_to_console($page);
        $tmp[0]=array($page[0].'_'.$this->kodmenu.'_'.$this->kodrasdel,$page[1],$page[2]);
        if($page[3]>0){  $dbl=$this->db; $tbl='news';
            $where=' WHERE kod=? ';
            $sql = "SELECT kod, kodmenu, kodrasdel, name,nameurl FROM ".$tbl." ".$where."  ORDER by sort";
            $stmt = $dbl->prepare($sql);
            $stmt->execute(array($page['3']));
            if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $tmp[1]=array($sms[0]['kod'].'_'.$sms[0]['kodmenu'].'_'.$sms[0]['kodrasdel'],$sms[0]['name'],$sms[0]['nameurl']);
            }
        }
        return $tmp;
    }

    private function selectTypArticle($sms,$itogname){ $tmp=array('','');
        if($sms['type']==1){$tmp=$this->makeArticle($sms,$itogname);}
        if($sms['type']==2){$tmp=$this->makeGalery($sms,$itogname,'news');}
        return $tmp;
    }
    private function makeGalery($sms,$itogname,$tbl){$masspict=array(); $allpage=array();
        if(file_exists('../adm/mod/class/var_alt.php')) {$put='../adm/mod/class/var_alt.php';}
        else{$put='./adm/mod/class/var_alt.php';}
        include ($put);
        $dbl=$this->db; $tblimg=$picturTbl[$tbl];
        $putimg=str_replace('../..','',$picturKat[$tbl]);
        $where=' WHERE kodrasdel=? ';
        $sql = "SELECT * FROM ".$tblimg." ".$where."  ORDER by sort";
        $stmt = $dbl->prepare($sql);
        $stmt->execute(array($sms['kod']));
        if ($pic = $stmt->fetchAll(PDO::FETCH_ASSOC)){
            foreach ($pic as $row){
                $masspict[]='<td><a class="linkpictur" data-fancybox="group" title="'.$row['note'].'" href="'.$putimg.$row['name'].'"><img src="'.$putimg.$row['name_small'].'" ></a></td>';
            }
            $limpage=$this->PictOnPage; $limrow=$this->limpictonrow;
            $perem=0;$stroka=array();$page=array();$line=array();
            foreach ($masspict as $row){
                $line[]=$row;
                if(count($line)==$limrow){$page[].='<tr>'.implode('',$line).'</tr>';$line=array();}
                if(count($page)==($limpage/$limrow)){$allpage[].='<table class="galtablpage">'.implode('',$page).'</table>';$page=array();}

            }
            if(count($line)){$page[].='<tr>'.implode('',$line).'</tr>';}
            if(count($page)){ $allpage[].='<table class="galtablpage">'.implode('',$page).'</table>';}

            $article=htmlspecialchars_decode($sms['post']); $book='';
            for($i=0;$i<count($allpage);$i++) {
                if($i==0){$styl='current '; $date = date_create($sms['data']);} else {$styl='';$article='';}
                $book.='<div id="page_'.($i+1).'" class="secstr  '.$styl.'" name="'.$itogname.'">'.
                    /* '<img class="blokpage" src="/img/blokpage.jpg">'.*/
                    '<div class="txt_block_head">'.$sms['name'].'</div>'.
                    '<div class="txt_block_date">'.date_format($date,'d-m-Y').'</div>'.
                    '<div class="txt_block">'. $article. $allpage[$i].'</div>'.
                    '<div class="txt_block_str">'.($i+1).' из '.count($allpage).'</div></div>';
            }
            $this->makeOpenGraph($sms);
            return array($book,$this->opengraph);
        }


        $article=htmlspecialchars_decode($sms['post']); $book='';
    }

    private function makeArticle($sms,$itogname){
        $tmp=htmlspecialchars_decode($sms['post']); $book='';
        $pieces = explode("[_page]", $tmp);
        //debug_to_console($sms);
        for($i=0;$i<count($pieces);$i++) {
            if($i==0){$styl='current '; $date = date_create($sms['data']);} else {$styl='';}
            $book.='<div id="page_'.($i+1).'" class="secstr  '.$styl.'" name="'.$itogname.'">'.
               /* '<img class="blokpage" src="/img/blokpage.jpg">'.*/
                '<div class="txt_block_head">'.$sms['name'].'</div>'.
                '<div class="txt_block_date">'.date_format($date,'d-m-Y').'</div>'.
                '<div class="txt_block">'.  $pieces[$i].'</div>'.
                '<div class="txt_block_str">'.($i+1).' из '.count($pieces).'</div></div>';
        }
       // $book='<div id="mainpages" class="mainpages" name="'.'">'.$book.'</div>';
        $this->makeOpenGraph($sms);
        return array($book,$this->opengraph);
    }

    private function makeArticleAll($sms,$itogname,$tbl,$pole){
        $tmp=''; $masstr=array(); $masspage=array();
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
        $opengr=1;
        foreach ($sms as $row){
            if($redirect=$row['redirect']) {$masstr[]=$this->redirect($row,1);}
           else{ $stmt->execute(array($row['kod']));
                if ($sms2 = $stmt->fetchAll(PDO::FETCH_ASSOC)) {$namepict=$sms2[0]['name_small'];}
                else {$namepict='/nopict.jpg';}
                $id=$row['kod'].'_'.$row['kodmenu'].'_'.$row['kodrasdel'];
                    if($opengr){ $this->makeOpenGraph($row); $opengr=0; }
                $url=$this->makeUrlArt($id).'/'.$this->CMP($row,'name');
                $tmp='<tr class="tablnews" name="'.$id.'">';
                $tmp.='<td><a class="linkarticle" href="'.$url.'"><img src="'.$putimg.$namepict.'"></a></td>';
                $tmp.='<td><span class="dalee"><a class="linkarticle" href="'.$url.'">'.$row['name'].'.</a> </span> <div class="description">'.$row[$pole].'</div></td></tr>';
                $masstr[]=$tmp;
            }

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
               /* '<img class="blokpage" src="/img/blokpage.jpg">'.*/
                '<div class="txt_block_head">'.$this->namerasdel.'</div>'.
                '<div class="txt_block">'.  $masspage[$i].'</div>'.
                '<div class="txt_block_str">'.$this->makeStr($i,count($masspage)).'</div></div>';
        }
        return array($book,$this->opengraph);
    }

    private function makeStr($nomer, $all){
        $temp = '';
        for($i=0;$i<$all;$i++){
            if($i==$nomer){$temp.='<span id="page_'.$i.'" class="nompagecurrent">'. ($i+1) .'</span>';}
            else{$temp.='<span id="page_go_'.$i.'" class="nompagelink">'.($i+1).'</span>';}
        }
        return $temp;
    }
    private function redirect($mass,$vybor){
        $dbl=$this->db; $tbl='news'; $where=' WHERE kod=? ';$pole='description';
        $sql = "SELECT * FROM ".$tbl." ".$where."  ORDER by sort";
        $stmt = $dbl->prepare($sql);
        $stmt->execute(array($mass['redirect']));
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $var_alt=$this->selectVariable($tbl);
            $tblimg = $var_alt[0];
            $putimg=str_replace('../..','',$var_alt[1]);
            $where = ' WHERE kodrasdel=? ';
            $sql = "SELECT * FROM " . $tblimg . " " . $where . "  ORDER by sort";
            $stmt2 = $dbl->prepare($sql);
            $stmt2->execute(array($sms[0]['kod']));
            if ($smsimg = $stmt2->fetchAll(PDO::FETCH_ASSOC)) {$namepict=$smsimg[0]['name_small'];}
            else {$namepict='/nopict.jpg';}
                if($vybor){
                    $idfake=$mass['kod'].'_'.$mass['kodmenu'].'_'.$mass['kodrasdel'];
                    $idtrue=$sms[0]['kod'].'_'.$sms[0]['kodmenu'].'_'.$sms[0]['kodrasdel'];
                    $url=$this->makeUrlArt($idtrue).'/'.$this->CMP($sms[0],'name');
                    $tmp='<tr class="tablnews" name="'.$idfake.'">';
                    $tmp.='<td><a class="linkarticle" href="'.$url.'"><img src="'.$putimg.$namepict.'"></a></td>';
                    $tmp.='<td><span class="dalee"><a class="linkarticle" href="'.$url.'">'.$sms[0]['name'].'.</a> </span> <div class="description">'.$sms[0][$pole].'</div></td></tr>';
                }
                else {$idtrue=$sms[0]['kod'].'_'.$sms[0]['kodmenu'].'_'.$sms[0]['kodrasdel'];
                    $tmp=$this->selectTypArticle($sms[0],$idtrue);}
            return $tmp;
        }
        return false;
    }
    private function selectVariable($tbl){ $mass=array();
        if(file_exists('../adm/mod/class/var_alt.php')) {$put='../adm/mod/class/var_alt.php';}
        else{$put='./adm/mod/class/var_alt.php';}
        include ($put);
        $mass=array($picturTbl[$tbl],$picturKat[$tbl]);
        return $mass;
    }


    private function makeOpenGraph($sms){
        $this->opengraph['keyw']=htmlspecialchars_decode($sms['keywords']);
        $this->opengraph['image']=$this->makeOpenGraphImg($sms,'news');
        $this->opengraph['title']=$sms['name'];
        $this->opengraph['description']=$sms['description'];
        //$this->opengraph['url']='http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        $this->opengraph['site_name']='Japson\'s Undeground';
    }

    private function makeOpenGraphImg($sms,$tbl){
        if(file_exists('../adm/mod/class/var_alt.php')) {$put='../adm/mod/class/var_alt.php';}
        else{$put='./adm/mod/class/var_alt.php';}
        include ($put);
        $dbl=$this->db; $tblimg=$picturTbl[$tbl];
        $putimg=str_replace('../..','',$picturKat[$tbl]);
        $where=' WHERE kodrasdel=? ';
        $sql = "SELECT * FROM ".$tblimg." ".$where."  ORDER by sort";
        $stmt = $dbl->prepare($sql);
        $stmt->execute(array($sms['kod']));
        if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {$namepict=$row[0]['name_small'];}
        else {$namepict='nopict.jpg';}
        return  'http://'.$_SERVER['SERVER_NAME'].$putimg.$namepict;
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
        if(count($mass)==0){return array(0,0,0);}
        $menu=0; $rasd=0; $article=0; $yakor=0;
        if(count($mass)>1) { $menu=$this->findMenu($mass[1],$tbl,0,0);}
        if(count($mass)>2) { $rasd=$this->findMenu($mass[2],'rasdel',$menu,0);}
        if(count($mass)>3) { $article=$this->findMenu($mass[3],'news',$menu,$rasd);}
       // debug_to_console($mass);
      //  $temp=$mass[count($mass)-1];
      //  if(substr($temp,0)=='#') {$yakor=substr($temp,1,3);}
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
                if ($tmp==$namemenu) {$kod=$row['kod'];
                    switch ($tbl) {
                        case 'mainmenu': $this->cofmen=$row['name']; break;
                        case 'rasdel': $this->cofrasd=$row['name']; break;
                    }
                }
            }

        }
        return $kod;
    }

    private function makeUrlArt($id){ // делаем ссылку по кодам меню+раздел
        $url='';
        $mass=explode('_',$id);
       // debug_to_console($this->mainmenu);
        foreach ($this->mainmenu as $row) { if($row['kod']==$mass[1]) $url.='/'.$row['nameurl']; }
        foreach ($this->allrasdel as $row) { if($row['kod']==$mass[2]) $url.='/'.$row['nameurl']; }
       // debug_to_console($mass); debug_to_console($url);
        return $url;
    }
    //----------------make playlists--------------

    private function makePlayList($param){
        $dbl=$this->db; $cassettes=array();
        if($param) {$where=' WHERE rol='.$param;} else{$where='';}
        $sql = "SELECT * FROM userlist ".$where."  ORDER by sort";
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($sms as $row) {
                $where=' WHERE (uid=? AND provider=? )';
                $user=$this->userList($row['userm'],$row['provider'],'comuser',$where);
                if(count($user)){
                    if(strlen($user[0]['nick'])){$nick=$user[0]['nick'];}
                    else{$nick=$user[0]['firstname'].' '.$user[0]['lastname'];}
                    $user=$nick.': ';}
                else{$user='Unknown: ';}
                $namelist=$row['namelist'];
               // $songs=$this->songUserList($row['arrsongs'],$row['arrsides'],'punkt');
                $cassettes[]=array($user,$namelist,$row['kod'],$row['checker']);
            }
        }
        return $cassettes;
    }
    private function userList($user,$prov,$tbl,$where){
        $dbl=$this->db;
        $sql = "SELECT * FROM ".$tbl.' '.$where."  ";
        $stmt = $dbl->prepare($sql);
        $stmt->execute(array($user,$prov));
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return $sms;
        } else {return array();}
    }



    private function makehtmlList($mass,$itogname){
        $tmp=''; $cass=array();
        foreach ($mass as $value){
            $tmp='<div class="cass_list" id="cassette'.$value[2].'"><div class="cass_author">'.$value[0].'</div>';
            if(strlen($value[1])>260){$name=mb_strcut($value[1],0,220,"UTF-8").'...';}else{$name=$value[1];}
           // $name=iconv($name,)
            $tmp.='<div class="cass_name" title="'.$value[1].'">'.$name.'</div>';
            if($value[3]==1){$classok='ok'; $titl='В каталоге';}
            elseif($value[3]==2){$classok='class';$titl='Классика';}
            else{$titl='На рассмотрении';$classok='nope';}
            $tmp.='<div class="cass_check '.$classok.'" title="'.$titl.'"></div>';
            $tmp.='</div>';
            $cass[]=$tmp;
        }
        $limit=$this->limalbumpage; $cont=0; $tmp='';
        $masspage=array();
        foreach($cass as $row){
            $tmp.=$row; $cont=$cont+1;
            if($cont==$limit) {$cont=0; $masspage[]=$tmp; $tmp='';}
        }
        if(strlen($tmp)>0) $masspage[]=$tmp;
        $book='';// debug_to_console(count($masspage));
        for($i=0;$i<count($masspage);$i++) {
            if($i==0){$styl='current ';} else {$styl='';}
            $book.='<div id="page_'.($i+1).'" class="secstr  '.$styl.'" name="'.$itogname.'">'.
                /* '<img class="blokpage" src="/img/blokpage.jpg">'.*/
                '<div class="txt_block_head">Каталог кассет</div>'.
                '<div class="txt_block">'.  $masspage[$i].'</div>'.
                '<div class="txt_block_str">'.$this->makeStr($i,count($masspage)).'</div></div>';
        }
        return array($book,$this->opengraph);
    }

    public function db(){ return $this->db;        }
    public function nametabl(){ return $this->nametabl;        }

}

?>