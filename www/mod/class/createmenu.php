<?php
class createMenu{
    private $db;
    private $nametabl;
    public $mainmenu;
    public $prefix;
    public $kodrasdel; public $kodmenu;

    public function __construct($nmtbl,$dbh) {
        // $this->sql ="SELECT * FROM ".$nametabl."";
        $this->db=$dbh; $this->nametabl=$nmtbl;
        $this->prefix='http://'.$_SERVER["HTTP_HOST"].'/';
    }
    public function initRasdMen($kodrasdel,$kodmenu){
        $this->kodmenu=$kodmenu;
        $this->kodrasdel=$kodrasdel;
    }

    public function massivMenu($where){  //make array main
        $dbl=$this->db; $mass=array();
        $sql = "SELECT * FROM ".$this->nametabl." ".$where."  ORDER by sort";
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            //debug_to_console($sms);
            foreach ($sms as $row) {
                $mass[]=array('kod'=>$row['kod'], 'name'=>$row['name'],'nameurl'=>$row['nameurl'],'titlepage'=>$row['titlepage'],'rol'=>$row['rol']);
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
                $mass[]=array('kod'=>$row['kod'], 'name'=>$row['name'],'nameurl'=>$row['nameurl'],'titlepage'=>$row['titlepage'],'rol'=>$row['rol'],'pictur'=>$url,'kodrasdel'=>$this->kodrasdel,'kodmenu'=>$this->kodmenu);
            }
        }
        $this->mainmenu=$mass;
    }
    private function pictUrl($kod){ // получить урл картинки полный
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
        } else {$tmpurl='';}
        return $tmpurl;
    }
}

?>