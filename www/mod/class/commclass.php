<?php
class Comment {
    private $db;
    private $nametabl;
    private $tbluser;
    public $prefix;
    private $userid;
    public $place; //m r a
    private $maxtext;
    private $message;
    private $nomerpost=0;
    private $uroven=0;

    public function __construct($nmtbl,$dbh) {
        $this->db=$dbh; $this->nametabl=$nmtbl;
        $this->tbluser='comuser';
        $this->maxtext=4000;
        $this->prefix='http://'.$_SERVER["HTTP_HOST"].'/';
    }

    public function commInput(){
        $tmp='<div class="comm_input"><label class="commarea" for="textarea1">Введите комментарий: </label> <textarea id="textarea1"> </textarea><div class="buttcomm"> <button class="buttonmycar" id="buttoncomm" title="Save"></button> <button class="buttonrefreshcar" id="buttonrefr" title="Refresh"></button></div></div>';
        return $tmp;
    }
    public function commList(){
    $tmp='<div class="comlist"> 45654654</div>';
        return $tmp;
    }

    public function checkOperator(){  //проверка что может юзер
       // debug_to_console($_SESSION['ulogin']);
        if(($_SESSION['jlogin']['is_auth']!=1)) {return array(0,'user isnt on');}
        $iduser=$this->checkUser($_SESSION['jlogin']);
        if($iduser[0]==0) {return $iduser;}
        else{ // добавление комментария
            $this->userid=$iduser[1];
            return $iduser;
        }
    }
    private function checkUser($userarray){ // сверка данных
        $error='';
        $user=$userarray['profile'];
        $dbl=$this->db;
        $sql = 'SELECT * FROM '.$this->tbluser.' WHERE provider = ? AND uid = ?';
        $stmt = $dbl->prepare($sql);
        $stmt->execute(array($user['provider'],$user['uid']));
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            if($sms[0]['auntf']!=$user['ident']) {return array(0,'you have login on a another device');}
            if($sms[0]['lock']==1) {return array(0,'you have spamed me. i am remember');}
            return array(1,$sms[0]['id']);
        }else{
            return array(0,'no reg');
        }
    }

    public function checkPart($part){ // проверка частей: меню раздел статья
        $tmp=1;
        foreach($part as $value){
            if(!is_numeric($value)){$tmp=0;}
        }
        if($tmp){$this->place=$this->sortMRA($part);}
        return array($tmp,'part not correct');
    }
    private function sortMRA($part){ //sort m r a
        if($part[2]){$retarr=array($part[1],$part[2],$part[0]);}
        if(!$part[2]){
            if($part[1]){$retarr=array($part[1],$part[0],$part[2]);}
            else{$retarr=array($part[0],$part[1],$part[2]);}
        } return $retarr;
    }

    public function checkTxt($txt)    { // проверка текста
        $tmp=1;
        if(strlen($txt)){
        $dbl=$this->db;
        $newtxt =($txt);// $dbl->quote($txt);
        $this->message=substr($newtxt, 0, $this->maxtext);}
        else{$tmp=0; }
        return array($tmp,'no text');
    }

    public function saveComment()    { // record текста
        $ider=0;
        $dbl=$this->db;
        $dat=date('Y-m-d H:i:s');$uroven=0;$post=0; //toLocaleString() 21.05.2018, 0:20:20
        // toString() Mon May 21 2018 00:20:20 GMT+0700
        //  Возвращает время и часовой пояс.toTimeString() 00:20:20 GMT+0700
        $keys=array('userid','comment','menu','rasdel','article','date','uroven','post');
        $quest=array('?','?','?','?','?','?','?','?');
        $znach=array($this->userid,$this->message,$this->place[0],$this->place[1],$this->place[2],$dat,$this->uroven,$this->nomerpost);
        $sql = 'INSERT INTO '.$this->nametabl.' ('.implode(',',$keys).') VALUES ('.implode(',',$quest).')';
        $stmt = $dbl->prepare($sql);
        $stmt->execute($znach);
        $ider = $dbl->lastInsertId();
        return array($ider,'no insert');
    }
    public function nomerPost($post){ // если есть номер то уровень поста
        $dbl=$this->db;
        $sql = 'SELECT * FROM '.$this->nametabl.' WHERE id=? Order BY date';
        $stmt = $dbl->prepare($sql);
        $stmt->execute(array($post));
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            if($sms[0]['post']>0) {$this->nomerpost=$sms[0]['post'];}
            else{$this->nomerpost=$post;}
            $this->uroven=1;
        }
    }

    public function nametabl() { //название таблицы
        return $this->nametabl;
    }
    public function db() { //ссылка на базу
        return $this->db;
    }
    public function tbluser() { //название таблицы
        return $this->tbluser;
    }
}
?>