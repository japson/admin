<?php
class dirPunkt{
    private $db;
    private $nametabl;
    private $maintable;
    private $menutable;
    private $menu;
    private $rasdel;private $allrasdel;
    public $allmakedir=array() ;
    public $actselect;


        public function __construct($nmtbl,$dbh) {
            $this->db=$dbh; $this->nametabl=$nmtbl;
            $this->maintable='rasdel';
            $this->menutable='mainmenu';
        }

    public function selectName($tbl,$pole,$where){
            $dbh=$this->db; $mass=array();
            $sql='SELECT * FROM '.$tbl. $where.' ORDER by sort';
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
        if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($sms as $row) {
                //  debug_to_console($sql);
                    $mass[] = array($row['kod'], $row['name'], $row['kodmenu'], $row['kodrasdel']);

            }
        }
            return $mass;
        }
        public function buildPutMini($menuarr,$rasdarr) { // проверить mini version
            $this->menu=$menuarr; $this->rasdel=$rasdarr;
            $this->allrasdel=$rasdarr;
        }
        public function buildPut($menuarr,$rasdarr){ // проверить промежуточные разделы между переходами если все то не надо
            $this->menu=$menuarr; $this->rasdel=$rasdarr;
                    $visiblefolder=array();
            foreach($rasdarr as $row) {
                $tmp=$row;
                $visiblefolder[]=$tmp;
                while($tmp[3]!=0){
                    $tmp=$this->checkFolder($tmp,'rasdel');
                //    debug_to_console($tmp);
                    $visiblefolder[]=$tmp;
                }
            }
            $newres=array();
            $result = $this->unique_multidim_array($visiblefolder,0);
                foreach ($result as $key=>$item) {$newres[]=$item;}
                $this->allrasdel=$newres;
            return $newres;
        }

        private function checkFolder($tmp,$tbl){ // к промежуточные разделы относится
            $dbh=$this->db; $mass=array();
            $sql='SELECT * FROM '.$tbl.' Where kod='.$tmp[3].' AND kodmenu='.$tmp[2].' ORDER by sort' ;
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
           // debug_to_console($stmt->fetchAll(PDO::FETCH_ASSOC));
            if($sms2=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
                 //  debug_to_console($sms2[0]['kod'].'---'.$sms2[0]['name']);
                $mass =array($sms2[0]['kod'],$sms2[0]['name'],$sms2[0]['kodmenu'],$sms2[0]['kodrasdel']);
            }
            return $mass;
        }
        private function makeAddMenuRasdelAll(){
            $menu=$this->menu; $tmp=array();
           foreach ($menu as $menrow){
               $this->allrasdel[]=array($menrow[0],$menrow[1],$menrow[0],$menrow[3] );
              // debug_to_console($this->allrasdel);
           }
           // $this->NewMenuArray=$tmp;
        }

        public function makeUrl(){ //создание массива всех отображениий директорий

            $menu=$this->menu; $rasdel=$this->rasdel; $allrasdel=$this->allrasdel;
            $tmp='';
         //   $this->makeAddMenuRasdelAll();
            foreach ($menu as $menrow){
              //  $temp=$this->makePunkts($menrow[0],0,'news',$this->NewMenuArray);
             //   debug_to_console($this->menu);
                $this->makeRasdel($menrow[0],$menrow[3]); //kod'name'kodmenu'kodrasdel
                $tmp.='<tr><td><div class="dirpunkt" id="'.$menrow[0].'_'.$menrow[3].'">'.$menrow[1].'</div></td><td>DIR</td><td></td></tr>';


            }

            $this->allmakedir['0_0']= '<table class="tblselectpunkt">'.$tmp.'</table>';

        }

        private function makeRasdel($kodmenu,$kodrasdel){ // собрать разделы в один список
            $dbh=$this->db; $mass=array();$temp=''; $return=array();
            $tblalias=$this->nameTbl('rasdel');
            $mass[]='<tr><td><div class="dirpunkt" id="'.'[_UPZAM]'.'">'.'...'.'</div></td><td></td><td></td></tr>';
                foreach ($this->allrasdel as $row){
                    if($row[2]==$kodmenu and $row[3]==$kodrasdel){
                        $mass[]='<tr id="__'.$row[2].'_'.$row[0].'"><td><div class="dirpunkt" id="'.$row[2].'_'.$row[0].'"  name="'.$row[0].'_'.$tblalias.'">'.$row[1].
                            '</div></td><td>DIR</td><td><div></div></td></tr>';//class="buttonselectdir" onclick="{'.$this->actselect.'}"
                       // $return=array($row[2].'_'.$row[0],$row[2].'_'.$row[3]);
                        $this->makeRasdel($kodmenu,$row[0]);
                    }
                }
                // золочил 3 строки ниже, отключив перебор Пунктов. строку с UPZAM перенес ниже
           // $temp=$this->makePunkts($kodmenu,$kodrasdel,'punkt',$this->allrasdel);$return=$temp[0];

         //   if(strlen($temp[1])>0) {$mass[]=$temp[1]; $return=$temp[0];}
         //   $mass[0]=str_replace('[_UPZAM]',$return[0],$mass[0]);
            $temp=$this->makePunkts($kodmenu,$kodrasdel,'news',$this->allrasdel);$return=$temp[0];
          //  debug_to_console($temp);
            if(strlen($temp[1])>0) {$mass[]=$temp[1]; $return=$temp[0];}
            $mass[0]=str_replace('[_UPZAM]',$return[0],$mass[0]);
                if(count($mass)>0){
                        $tmp=implode('',$mass);
                    //$this->allmakedir[]= array($return,'<table class="tblselectpunkt">'.$tmp.'</table>');
                    $this->allmakedir[$return[1]]= '<table class="tblselectpunkt">'.$tmp.'</table>';
                }
        }
        private function makePunkts($kodmenu,$kodrasdel,$tbl, $allrasd){ //добавить пункты в раздел
            if($tbl=='news'){$redirect=' AND redirect=0 ';} else{ $redirect='';}
            $dbh=$this->db; $mass='';$return=array();
            $tblalias=$this->nameTbl($tbl);
            //$tbl=$this->nametabl;
         //   debug_to_console($kodmenu.'-'.$kodrasdel);
            foreach ($allrasd as $row){
                if($row[2]==$kodmenu and $row[0]==$kodrasdel){
                    $return=array($row[2].'_'.$row[3],$row[2].'_'.$row[0]);
                }
                if(!count($return)){$return=array('0_0',$kodmenu.'_'.$kodrasdel);}
            }
            foreach ($this->rasdel as $row){

                if($row[2]==$kodmenu and $row[0]==$kodrasdel){
                    $sql='SELECT * FROM '.$tbl.' Where kodmenu='.$kodmenu.' AND kodrasdel='.$kodrasdel .$redirect.' ORDER by sort' ;
                    $return=array($row[2].'_'.$row[3],$row[2].'_'.$row[0]);
                   // debug_to_console($sql);
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
                        foreach ($sms as $row) {
                            if($tbl=='punkt') {
                                $mass .= '<tr id="' . $row['kod'] . '"><td><div class="filepunkt"  title="' . $row['artist'] . ' - ' . $row['title'] . '" name="'.$row['kod'].'_'.$tblalias.'">' . $row['artist'] . ' - ' . $row['title'] . '</div></td><td>file</td><td><div class="buttonselectdir" onclick="{' . $this->actselect . '}"></div></td></td>';
                            } else{
                                $mass .= '<tr id="' . $row['kod'] . '"><td><div class="postpunkt"  title="' . $row['name'] . '" name="'.$row['kod'].'_'.$tblalias.'">' . $row['name'] .  '</div></td><td>news</td><td><div class="buttonselectdir" onclick="{' . $this->actselect . '}"></div></td></td>';
                            }

                        }
                    }

                }
            } //end foreach
            foreach ($this->menu as $row){
                if($row[0]==$kodmenu and $row[3]==$kodrasdel){
                    $sql='SELECT * FROM '.$tbl.' Where kodmenu='.$kodmenu.' AND kodrasdel='.$kodrasdel .$redirect.' ORDER by sort' ;
                   // $return=array($row[2].'_'.$row[3],$row[2].'_'.$row[0]);
                    // debug_to_console($sql);
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
                        foreach ($sms as $row) {
                            if($tbl=='punkt') {
                                $mass .= '<tr id="' . $row['kod'] . '"><td><div class="filepunkt"  title="' . $row['artist'] . ' - ' . $row['title'] . '" name="'.$row['kod'].'_'.$tblalias.'">' . $row['artist'] . ' - ' . $row['title'] . '</div></td><td>file</td><td><div class="buttonselectdir" onclick="{' . $this->actselect . '}"></div></td></td>';
                            } else{
                                $mass .= '<tr id="' . $row['kod'] . '"><td><div class="postpunkt"  title="' . $row['name'] . '" name="'.$row['kod'].'_'.$tblalias.'">' . $row['name'] .  '</div></td><td>news</td><td><div class="buttonselectdir" onclick="{' . $this->actselect . '}"></div></td></td>';
                            }

                        }
                    }

                }
            } //end foreach




            return array($return,$mass);
        }


    private function unique_multidim_array($array, $key) { // уникальные значения из мультимассива
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
public function outAction($action){ //вывод функции действия

    if (strlen($action)>0) {
        $temp='<div class="col-sm-3 col-sm-offset-3"><button class="btn btn-success" onclick="{' ;
    $temp.=$action.'();}">OK</button></div>';
        $temp.='<div class="col-sm-3"><button class="btn btn-warning" onclick="{delOverley();}">Отмена</button></div>';
    }
    return $temp;
}

    public function nameTbl($tbl){ //алиас таблицы
        include('var_alt.php');
        $aliastabl='';
        foreach ($massTablAlias as $key=>$value){
            if($tbl==$key) {$aliastabl=$value;}
        }
        //debug_to_console($massTablAlias );
        return $aliastabl;
    }

    public function testLink($id){  // создание пути к текущему редиректу
        $dbh=$this->db; $temp='';
        $sql='SELECT * FROM '.$this->nametabl.' Where kod=?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array($id));
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $redir=$sms[0]['redirect'];
                $tablic = $this->nametabl;
                $sql='SELECT * FROM '.$tablic.' Where kod='.$redir;
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                    if($tablic=='punkt'){$nam='title';} else{$nam='name';}
                    $name = $sms[0][$nam];
                    $kodrasdel = $this->kodRasd($sms[0]['kodrasdel'], 'rasdel');
                    $kodmenu = $this->kodMenu($sms[0]['kodmenu'], 'mainmenu');
                    $temp='<div>Линк: '.$kodmenu.$kodrasdel.$name.'('.$redir.')'.'</div><div class="delredirect" title="Удалить redirect" id="'.$id.'"></div>';
                }

        }
        return $temp;
    }

    private function kodRasd($kod,$tbl){
        $dbh=$this->db; $tmp='';
        $sql='SELECT * FROM '.$tbl.' Where kod='.$kod;
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
      //  debug_to_console($sql );
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            if($sms[0]['kodrasdel']==0){ $tmp=$sms[0]['name'].'/';}
            else{
                $tmp=$this->kodRasd($sms[0]['kodrasdel'],'rasdel').$sms[0]['name'].'/';
            }
        }
        return $tmp;
    }
    private function kodMenu($kod,$tbl){
        $dbh=$this->db; $tmp='';
        $sql='SELECT * FROM '.$tbl.' Where kod='.$kod;
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        //  debug_to_console($sql );
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $tmp=$sms[0]['name'].'/';
                    }
        return $tmp;
    }

    public function saver($vvodata,$znach){
        $dbh=$this->db;
        $sql=' UPDATE '.$this->nametabl.' SET '.$vvodata.' WHERE kod=?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute($znach);
       // debug_to_console($stmt );
    }
}
?>
