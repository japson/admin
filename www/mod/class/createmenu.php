<?php
class createMenu{
    private $db;
    private $nametabl;
    public $mainmenu;
    public $prefix;

    public function __construct($nmtbl,$dbh) {
        // $this->sql ="SELECT * FROM ".$nametabl."";
        $this->db=$dbh; $this->nametabl=$nmtbl;
        $this->prefix='http://'.$_SERVER["HTTP_HOST"].'/';
    }

    public function massivMenu(){  //make array main
        $dbl=$this->db; $mass=array();
        $sql = "SELECT * FROM mainmenu WHERE vyvod=1 ORDER by sort";
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
}

?>