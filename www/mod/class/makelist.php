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
    public $maintimes=array();
    private $savsongs=array(); private $savsides=array();
    private $user; private $provider;

    public function __construct($nmtbl, $dbh)
    { $this->db=$dbh;
        $this->nametabl=$nmtbl;

    }
    public function checkSession(){
        if($_SESSION['jlogin']['is_auth'] == 1){
            //  debug_to_console($_SESSION['jlogin']['profile']['uid']);
            $this->findList($_SESSION['jlogin']['profile']['uid'],'userm');
        }else {$this->makeDefaultList();}
        // debug_to_console($_SESSION['jlogin']['profile']);
    }
    public function checkSCassete($kod,$criter){
        $this->findList($kod,$criter);
    }

    private function findList($uid,$criteriy){
        $dbl = $this->db;
        $sql = 'SELECT * FROM ' . $this->nametabl . ' WHERE '.$criteriy.' = ? ';
        $stmt = $dbl->prepare($sql);
        $stmt->execute(array($uid));
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                    $this->makeUserList($sms[0]['arrsongs'],$sms[0]['arrsides']);
        }else{
            $this->makeDefaultList();
        }
    }
    private function makeUserList($songs,$sides){
        $this->songs=explode(',',$songs);
        $tmp=explode(',',$sides);
        foreach ($tmp as $value){
            if($value=='2'){$this->sides[]=2;}
            else{$this->sides[]=1;}
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
                $this->maintitles[]=$sms[0]['title'].' - '.$sms[0]['artist'];
                $this->maintimes[]=$sms[0]['length'];
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
            if( is_numeric($songs[$i])) {$this->savsongs[]=$songs[$i]; $this->savsides[]=str_replace('side','',$sides[$i]);}
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

    // for list casssette
    public function checkuserList(){
        if($_SESSION['jlogin']['is_auth'] == 1){
            $this->user=($_SESSION['jlogin']['profile']['uid']);
            $this->provider=$_SESSION['jlogin']['profile']['provider'];
            $dbl = $this->db;
            $sql = 'SELECT * FROM ' . $this->nametabl . ' WHERE userm = ? ';
            $stmt = $dbl->prepare($sql);
            $stmt->execute(array($this->user));
            if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $this->makeUserList($sms[0]['arrsongs'],$sms[0]['arrsides']);
            }
        }
        // debug_to_console($_SESSION['jlogin']['profile']);

    }
    public function checkCurrentPlayList($tbl){
        $dbl = $this->db;
        $sql = 'SELECT * FROM ' . $tbl . ' WHERE userm = ? and provider=? and checker=0';
        $stmt = $dbl->prepare($sql);
        $stmt->execute(array($this->user, $this->provider));
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) { $tmp=$sms[0]['kod'];}
        else{$tmp=0; }
       // debug_to_console($tmp);
        return $tmp;
    }

    public function saveUserPlayList($tbl,$check,$name){
        $dbl = $this->db;
        if($check){
            $sql = 'UPDATE ' . $tbl . ' SET arrsongs=?, arrsides =?, namelist=?  WHERE kod = ? ';
            $znach=array(implode(',',$this->songs), implode(',',$this->sides),$name,$check);
        }else{
            $keys='arrsongs, arrsides, provider, userm, namelist'; $quest='?,?,?,?,?';
            $sql = 'INSERT INTO ' . $tbl . ' (' . $keys . ') VALUES (' . $quest . ');';
            $znach=array(implode(',',$this->songs), implode(',',$this->sides),$this->provider,$this->user,$name);
        }

        $stmt = $dbl->prepare($sql);
        $stmt->execute(($znach));
        return 1;
    }
    public function genList($kod){
        $dbl = $this->db; $tmp=array();
        $sql = 'SELECT * FROM ' . $this->nametabl . ' WHERE kod = ? ';
        $stmt = $dbl->prepare($sql);
        $stmt->execute(array($kod));
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $arrsongs=explode(',',$sms[0]['arrsongs']);
            $arrsides=explode(',',$sms[0]['arrsides']);

            $tmp=$this->popeList($arrsongs,$arrsides);
            $ret='<div class="pop_cass">';
            $ret.='<div class="nam_cass"><span class="names">'.$sms[0]['namelist'].
                '</span><span class="switchlist" id="list'.$kod.'" title="загрузить кассету"></span></div>';
            $ret.=$tmp;
            $ret.='<div class="aftercass"><div class="closecass" title="Закрыть" onclick="delOverley();"></div></div>';
            $ret.='</div>';
        }
        return $ret;
    }
    private function popeList($arrsongs,$arrsides){
        $tmp=$this->songUserList($arrsongs,$arrsides,$this->tblsong);
        if($tmp[0]>$tmp[1]) {$len=count($tmp[0]);}else{$len=count($tmp[1]);}
        $tablic='<table class="cass_tbl">';
        for($i=0;$i<$len;$i++){
            $tablic.='<tr>';
            if(strlen($tmp[0][$i])){$tablic.='<td>'.$tmp[0][$i].'</td>';}
            else{$tablic.='<td> </td>';}
            if(strlen($tmp[1][$i])){$tablic.='<td>'.$tmp[1][$i].'</td>';}
            else{$tablic.='<td> </td>';}
            $tablic.='</tr>';
        }
        $tablic.='</table>';
        return $tablic;
    }

    private function songUserList($songs,$sides,$tbl){
        $tmp=array(0,0); $side1=array(); $side2=array();
       // $songs=explode(',',$songs);
       // $sides=explode(',',$sides);
        $dbl = $this->db;
        $sql = 'SELECT * FROM ' . $tbl . ' WHERE kod = ?';
        $stmt = $dbl->prepare($sql);
        // foreach ($songs as $value){
        for($i=0;$i<count($songs);$i++) {
            $stmt->execute(array($songs[$i]));
            if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                if($sides[$i]==2){$side2[]=$sms[0]['artist'].': '.$sms[0]['title'];}
                else {$side1[]=$sms[0]['artist'].': '.$sms[0]['title'];}
            }
        }return array($side1,$side2);
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