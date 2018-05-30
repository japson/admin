<?php
class Social {
    private $db;
    private $nametabl;
    public $prefix;
    private $myid;
    private $sig;
    private $logoprovaider;
    private $massdat=array();
    public $session=array();

    public function __construct($nmtbl,$dbh) {
        $this->db=$dbh; $this->nametabl=$nmtbl;
        $this->prefix='http://'.$_SERVER["HTTP_HOST"].'/';
      //  $this->myid='76206';
      //  $this->sig='c052497084b20340babd5f8e57de182a'; //loginza japson 216345
    }
    public function tokenGet_old($token){ // старая функция не нужна
        $sig=md5($token.$this->sig);
        $authData = file_get_contents('http://loginza.ru/api/authinfo?token='.$token.'&id='.$this->myid.'&sig='.$sig);
        if ($authData===false) { $temp='error check token'; }
        else{
            $user = json_decode($authData);
            $tmp=$this->receiveInfo($user);
            if($tmp['uid'] && $tmp['provider']) {
                $this->checkRega();
                $this->makeInvite();
            }
          // debug_to_console($authData);

        }
    }
    public function tokenGet($token){
        $tmp=$this->receiveInfo($token);
        if($tmp['uid'] && $tmp['provider']) {
            $this->checkRega();
            $this->makeInvite();
        }
    }

    private function checkRega(){ //проверяю таблицу есть ли данная рега уид и провайдер
        $dbl=$this->db;
        $sol=$this->genSol();
        $sql = 'SELECT * FROM '.$this->nametabl.' WHERE provider = "'.$this->massdat['provider'].'" AND uid = "'.$this->massdat['uid'].'"';
       // debug_to_console($sol);
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            // todo: проверить имя мож изменилось
            $this->session=array('id'=>$sms[0]['id'],'uid'=>$sms[0]['uid'],'provider'=>$sms[0]['provider'],'name'=>$sms[0]['firstname'].' '.$sms[0]['lastname'],'logoprov'=>$this->logoprovaider,'ident'=>$sol);

        } else {
            foreach($this->massdat as $key=>$value) {
                $keys[] = $key ;//. '=?';
                $quest[]='?';
                $znach[] = htmlspecialchars($value);
            }
            // $sql = 'INSERT INTO '.$this->nametabl.' ('.implode(',',$keys).') VALUES ("'.implode(',',$znach).'")';
            $sql = 'INSERT INTO '.$this->nametabl.' ('.implode(',',$keys).') VALUES ('.implode(',',$quest).')';
          //  debug_to_console($znach);
            $stmt = $dbl->prepare($sql);
            $stmt->execute($znach);
            $ider = $dbl->lastInsertId();
            $this->session=array('id'=>$ider,'uid'=>$this->massdat['uid'],'provider'=>$this->massdat['provider'],'name'=>$this->massdat['firstname'].' '.$this->massdat['lastname'],'logoprov'=>$this->logoprovaider,'ident'=>$sol);
        }
        $this->updateUser($sol);
    }

    private function receiveInfo($user){ // получить  инфу из логинзы
        $this->massdat=array('uid' => $user->uid,'provider' => $user->network,'firstname'=>$user->first_name, 'identity'=>$user->identity,'lastname'=>$user->last_name,'email'=>$user->email);
$this->logoprovaider=$user->network;

        return $this->massdat;
    }

    public function makeInvite(){
       // debug_to_console($this->session);
        $_SESSION['ulogin']['is_auth'] = 1;
        $_SESSION['ulogin']['profile'] = $this->session;

    }
    public function exitSol(){
        $sol=$this->genSol();
        $this->massdat['provider']=$_SESSION['ulogin']['profile']['provider'];
        $this->massdat['uid']=$_SESSION['ulogin']['profile']['uid'];
        $this->updateUser($sol);
    }
    private function genSol(){
        $seed = sha1(mt_rand());
        return $seed;
    }
    private function updateUser($sol){
        $dbl=$this->db;
        $sql = 'UPDATE '.$this->nametabl.' SET auntf="'.$sol.'" WHERE provider = "'.$this->massdat['provider'].'" AND uid = "'.$this->massdat['uid'].'"';
        $stmt = $dbl->prepare($sql);
        $stmt->execute();

    }

    public function outName($user){
        switch ($user['logoprov']){
            case 'facebook': $tmp='sc_f.png';break;
            case 'vkontakte': $tmp='sc_vk.png';break;
            case 'googleplus': $tmp='sc_g.png';break;
            case 'odnoklassniki': $tmp='sc_o.png';break;
            case 'mailru': $tmp='sc_mail.png';break;
            case 'twitter': $tmp='sc_t.png';break;
            case 'google': $tmp='sc_gog.png';break;
            case 'yandex': $tmp='sc_ya.png';break;
            case 'instagram': $tmp='sc_inst.png';break;
        }
        $logo=' <img class="socimg" src="/img_n/'.$tmp.'">';
        $tmp=$logo.$user['name'].' <a id="outsession" href="" onclick="ExitSocial();return false;">Выйти</a>';
        return $tmp;
    }
    public function outLogSoc($param){
        if($param) {
            $tmp = 'Comments only with:<br>
            <div id="uLogin22addfea" data-ulogin="display=panel;fields=first_name,last_name,email;theme=flat;providers=vkontakte,odnoklassniki,mailru,facebook,google,instagram;hidden=twitter,yandex,googleplus;redirect_uri=;callback=callUlogin"></div> ';
        }else{
        $tmp ='Comments only with:<br> <div id="uLogin_22addfea" data-uloginid="22addfea"></div>';
        }
        return $tmp;
    }
}

?>