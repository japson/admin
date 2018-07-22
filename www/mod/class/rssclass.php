<?php

class rssclass extends createMenu
{
    private $picturarr=array();
    public $news=array();

    public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
        // $this->prefix=$_SERVER['SERVER_NAME'];
        // debug_to_console($this->prefix);
        $this->massivMenu('');
        $pictsearch=1;
        $this->renameTabl('rasdel');
        $this->massivRasdel('',$pictsearch); // where rol!=5
        $this->renameTabl($nmtbl);
    }
    public function createRss($where){
        $dbl=$this->db();
        $sql = "SELECT * FROM ".$this->nametabl(). ' '.$where;
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $this->getPictur($sms);
            foreach($sms as $value){
                $tmp= $this->getNews($value);
                if(count($tmp)){$this->news[]=$tmp;}
            }

        }
    }
    private function getNews($mass){
        $dbl=$this->db(); $tmp=array();
        $ssyl=array($mass['kodmenu'],$mass['kodrasdel']);
        $tmpurl=$this->makeUrlArt($ssyl);
        if($tmpurl!='nope_nope') {
            $url = $tmpurl . '/' . $this->CMP($mass, 'name');
            $tmp['url'] = $url;
            $tmp['description'] = $mass['description'];
            $tmp['data'] = $mass['data'];
            $tmp['title'] = $mass['name'];
            $tmp['pictur'] = $this->picturarr[$mass['kod']];
            $tmp['keyw'] = $mass['keywords'];
            $tmp['tag'] = str_replace('/', '#', $tmpurl);
        }
    return $tmp;
    }

    private function makeUrlArt($id){ // делаем ссылку по кодам меню+раздел
        $url='';
        $mass=$id;
      //  debug_to_console($this->allrasdel);
        foreach ($this->mainmenu as $row) { if($row['kod']==$mass[0]) $url.='/'.$row['nameurl']; }
        foreach ($this->allrasdel as $row) {
            if($row['kod']==$mass[1]){
                $url.='/'.$row['nameurl']; if($row['rol']==5){$url='nope_nope';} }
        }
        // debug_to_console($mass); debug_to_console($url);
        return $url;
    }
    private function getPictur($mass){
        if(file_exists('../adm/mod/class/var_alt.php')) {$put='../adm/mod/class/var_alt.php';}
        else{$put='./adm/mod/class/var_alt.php';}
        //include'../adm/mod/class/var_alt.php';
        include ($put);
        $dbl=$this->db();
        $dir=str_replace('../..','', $picturKat[$this->nametabl()]);
        $tblimg=$picturTbl[$this->nametabl()];
        $where=' Where kodrasdel=?';
        $sql = "SELECT * FROM ".$tblimg." ".$where."  ORDER by sort";
        $stmt = $dbl->prepare($sql);
        foreach ($mass as $value){
            $stmt->execute(array($value['kod']));
            if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $tmpurl=$dir.$sms[0]['name_small'];
            } else {$tmpurl='/img_n/nopict.jpg';}
            $this->picturarr[$value['kod']]=$tmpurl;
        }

    }

}
?>