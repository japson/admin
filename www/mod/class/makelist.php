<?php
class makelist{
    private $db;
    private $tblsong='punkt';
    private $nametabl;
    public $songs=array();
    private $sides=array();
    public $mainsides=array();
    public $mainsong=array();
    public $maintitles=array();
    private $savsongs=array(); private $savsides=array();

    public function __construct($nmtbl, $dbh)
    { $this->db=$dbh;
        $this->nametabl=$nmtbl;

    }
    public function checkSession(){
        if($_SESSION['jlogin']['is_auth'] == 1){
            //  debug_to_console($_SESSION['jlogin']['profile']['uid']);
            $this->findList($_SESSION['jlogin']['profile']['uid']);
        }else {$this->makeDefaultList();}
        // debug_to_console($_SESSION['jlogin']['profile']);
    }

    private function findList($uid){
        $dbl = $this->db;
        $sql = 'SELECT * FROM ' . $this->nametabl . ' WHERE kod = ? ORDER BY side ASC, sort ASC';
        $stmt = $dbl->prepare($sql);
        $stmt->execute(array($uid));
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {

        }else{
            $this->makeDefaultList();
        }
    }

    private function makeDefaultList(){
        $this->songs=array('45','50','54');
        $this->sides=array(1,1,1);
    }

    public function makeList(){
        // $put=$this->findPut('punkt');
        $dbl = $this->db; $tbl=$this->tblsong;
        $songs=$this->songs;
        $sql = 'SELECT * FROM ' . $tbl . ' WHERE kod = ?';
        $stmt = $dbl->prepare($sql);
        foreach ($this->songs as $value){
            $stmt->execute(array($value));
            if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $this->mainsong[]=$sms[0]['put']; //todo: make external links
                $this->maintitles[]=$sms[0]['title'].'-'.$sms[0]['artist'];
            }
        }
        foreach ($this->sides as $value){
            if($value=='2'){$this->mainsides[]='side2';}
            else{$this->mainsides[]='side1';}
        }

    }
    public function saveList($sides,$songs){
        $this->checkInput($sides,$songs); $check=0;
        $tmp=$this->findUser();
        if($tmp['type']!='error'){ $check=$this->saveIndb($tmp);}
        return $check;
    }

    private function checkInput($sides,$songs){
        for($i=0;$i<count($songs);$i++){
            if( is_numeric($songs[$i])) {$this->savsongs[]=$songs[$i]; $this->savsides[]=$sides[$i];}
        }
    }
    private function findUser(){
        if(strlen( $uid=$_SESSION['jlogin']['profile']['uid']) && strlen($prov=$_SESSION['jlogin']['profile']['provider'])){
            $dbl = $this->db;
            $sql = 'SELECT * FROM ' . $this->nametabl. ' WHERE userm = ? and provider=?';
            $stmt = $dbl->prepare($sql);
            $stmt->execute(array($uid,$prov));
            if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $mass=array('type'=>'update','uid'=>$uid,'provider'=>$prov);
            }else{$mass=array('type'=>'insert','uid'=>$uid,'provider'=>$prov);}
        }else{$mass=array('type'=>'error');}
      //  $mass=array('type'=>'error');
    return $mass;
    }
    private function saveIndb($mass){
        $dbl = $this->db;
        if($mass['type']=='update'){
            $sql = 'UPDATE ' . $this->nametabl . ' SET arrsongs=?, arrsides =?  WHERE provider = ? AND userm = ?';
        }else{
            $keys='arrsongs, arrsides, provider, userm'; $quest='?,?,?,?';
            $sql = 'INSERT INTO ' . $this->nametabl . ' (' . $keys . ') VALUES (' . $quest . ');';
        }
        $znach=array(implode(',',$this->savsongs), implode(',',$this->savsides),$mass['provider'],$mass['uid']);
        $stmt = $dbl->prepare($sql);
        $stmt->execute(($znach));
        return 1;
    }


    private function findPut($tbl){// nenado
        $prefix='';
        if (file_exists('../adm/mod/class/var_alt.php')) {
            require_once($prefix.'../adm/mod/class/var_alt.php');
        }else{
            require_once($prefix.'/adm/mod/class/var_alt.php');
        }
        return str_replace('../..','',$massElements[$tbl]);
    }
}
?>