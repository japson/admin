<?php
class OutputComment extends Comment{
    public $commZero=array();
    public $commOne=array();
    public $userarray=array();
    public $massitog=array();

    public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }

    public function loadPageCom(){
      //  return $this->commInput();
        //$listcomm=$comm->commList($itogname);
        //debug_to_console('as'.$itogname);

    }
    public function inputCom(){
        $user=$this->checkOperator();
        if(!$user[0]){return ($user);}
        else{ // продолжить ввод
            $post = preg_replace('~[^0-9]+~','',$_POST['updatcom']);
            if($post>0){$this->nomerPost($post);}

            if($_POST['part']){
                list($first, $second, $third) = explode('_', $_POST['part']);
                $masscheckPart=$this->checkPart(array($first, $second, $third));
            } else{$masscheckPart=(array(0,'no parties'));}
            // debug_to_console($itogname);
            if($_POST['typ']){
                $masscheckTyp=$this->checkTxt($_POST['typ']);
            }else{$masscheckTyp=(array(0,'no txt'));}
            //debug_to_console($masscheckTyp);

            if($masscheckTyp[0] && $masscheckPart[0]){// все зашибись
                $masscheck=$this->saveComment();

            }else{ $masscheck=array(0,$masscheckTyp[1].' : '.$masscheckPart[1]);
                //echo json_encode($masscheck);
            }
            return $masscheck;
        }
    }
    public function outCom(){

        if($_POST['part']){
            list($first, $second, $third) = explode('_', $_POST['part']);
            $masscheckPart=$this->checkPart(array($first, $second, $third));
            if(!$this->makeListComm()) {$masscheckPart=(array(0,'no pologeno'));};

        } else{$masscheckPart=(array(0,'no parties'));}
        return $masscheckPart;

    }
    public function makeListComm(){ //лист комментов
        if($this->place[2]==0) {return 0;}
        $this->commZero=$this->makeArrayComment(0);
       // $this->commOne=$this->makeArrayComment(1);
        $this->makeHtmList();
       // debug_to_console($this->massitog);
       // debug_to_console($this->commOne);
      //  debug_to_console($this->userarray);
        return 1;
    }
    private function makeArrayComment($uroven){
        $dbl=$this->db();
        $sql = 'SELECT * FROM '.$this->nametabl().' WHERE menu = ? AND rasdel = ? AND article=? AND uroven=? Order BY date';
        $stmt = $dbl->prepare($sql);
        $mass=$this->place; $mass[]=$uroven;
        $stmt->execute($mass);
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($sms as $value){
                $dat=explode(' ',$value['date']);
                $masscom[]=array('id'=>$value['id'],'userid'=>$value['userid'],'comment'=>htmlentities($value['comment'], ENT_QUOTES, 'UTF-8'),'date'=>$dat[0],'post'=>$value['post']);
                $this->makeUserPost($value['userid']);
                $this->makeArrayCommentLow($value['id']);
            }

        } return $masscom;
    }
    private function makeArrayCommentLow($id){ // подчиненный массив
        $dbl=$this->db(); $tmp=array();
        $sql = 'SELECT * FROM '.$this->nametabl().' WHERE post = ? Order BY date';
        $stmt = $dbl->prepare($sql);
        $stmt->execute(array($id));
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($sms as $value) {
                $dat=explode(' ',$value['date']);
                $tmp[]=array('id'=>$value['id'],'userid'=>$value['userid'],'comment'=>htmlentities($value['comment'], ENT_QUOTES, 'UTF-8'),'date'=>$dat[0],'post'=>$value['post']);
                $this->makeUserPost($value['userid']);
            }

        }
        $this->commOne[$id]=$tmp;
    }

    private function makeUserPost($iduser){ $tmp=0;
        foreach ($this->userarray as $key=>$value){
            if($key==$iduser){$tmp=1;}
        }
        if(!$tmp){
            $dbl=$this->db();
            $sql = 'SELECT * FROM '.$this->tbluser().' WHERE id = ?';
            $stmt = $dbl->prepare($sql);
            $stmt->execute(array($iduser));
            if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $this->userarray[$iduser]=array($sms[0]['firstname'],$sms[0]['lastname'],$this->outName($sms[0]['provider']),$sms[0]['nick']);
            }
        }
    }

    private function outName($provider){
        switch ($provider){
            case 'fb': $tmp='sc_f.png';break;
            case 'vk': $tmp='sc_vk.png';break;
            case 'googleplus': $tmp='sc_g.png';break;
            case 'ok': $tmp='sc_o.png';break;
            case 'mail': $tmp='sc_mail.png';break;
            case 'twitter': $tmp='sc_t.png';break;
            case 'go': $tmp='sc_gog.png';break;
            case 'ya': $tmp='sc_ya.png';break;
            case 'instagram': $tmp='sc_inst.png';break;
        }
        $logo=' <img class="socimgcomm" src="/img_n/'.$tmp.'">';
        return $logo;
    }
    private function makeHtmList(){
        if(!count($this->commZero)) return false;
        foreach ($this->commZero as $item) {
            $name=$this->getName($item['userid']);
            $tmp='<div class="commzero">'.$name[1].'<div class="commview" id="main'.$item['id'].'"><span class="commname">'.$name[0].'</span><span class="commtext">'.$item['comment'].'</span></div>';
            $tmp.='<div id="'.$item['id'].'" class="commbutton"><button class="answercomm" title="Ответить"></button><span>'.$item['date'].'</span></div>';
            $tmp.='<div class="commaddopen commhidden" id="com'.$item['id'].'"><textarea></textarea><button class="commaddsend">>>></button></div>';
            $tmp.='</div>';
            $this->massitog[$item['id']]=$tmp;// сломается или нет сортировка?
            $this->makeHtmListLow($item['id']);
            }
    }

    private function makeHtmListLow($id){
        $tmp=$this->commOne[$id];
        if(count($tmp)){
            foreach ($tmp as $item) {
                $name=$this->getName($item['userid']);
                $tmp='<div class="commone">'.$name[1].'<div class="commview" id="main'.$item['id'].'"><span class="commname">'.$name[0].'</span><span class="commtext">'.$item['comment'].'</span></div>';
                $tmp.='<div id="'.$item['id'].'" class="commbutton"><button class="answercomm" title="Ответить"></button><span>'.$item['date'].'</span></div>';
                $tmp.='<div class="commaddopen commhidden" id="com'.$item['id'].'"><textarea></textarea><button class="commaddsend">>>></button></div>';
                $tmp.='</div>';
                $this->massitog[$item['id']]=$tmp;// сломается или нет сортировка?
            }
        }
    }

    private function getName($userid){
        $tmp=$this->userarray[$userid];
        if(strlen($tmp[3])){$nick=$tmp[3];}else{$nick=$tmp[0].' '.$tmp[1];}
        return array($nick,$tmp[2]);
    }

    public function buildList($mass){
        $tmp='';
        foreach ($mass as $item){$tmp.=$item; }
        return array($tmp,$this->commInput());
    }
}
?>