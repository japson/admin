<?php
class dirPunkt{
    private $db;
    private $nametabl;
    private $maintable;
    private $menutable;
    private $menu;
    private $rasdel;private $allrasdel;
    public $allmakedir=array() ;
    public $polesout=array() ;

        public function __construct($nmtbl,$dbh) {
            $this->db=$dbh; $this->nametabl=$nmtbl;
            $this->maintable='rasdel';
            $this->menutable='mainmenu';
        }

        public function havePunkt($tbl,$pole,$where){
            $dbh=$this->db;
            $sql='SELECT '.$pole.' FROM '.$this->nametabl.'  GROUP BY '.$pole.' '.$where;
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
               // debug_to_console($sql);
                   // $mass=[];
               $mass=$this->selectName($tbl,$sms,$pole);
            }
            return $mass;
        }

        private function selectName($tbl,$sms,$pole){
            $dbh=$this->db; $mass=array();
            $sql='SELECT * FROM '.$tbl.' Where kod=?' ;
            $stmt = $dbh->prepare($sql);
            foreach ($sms as $row){
              //  debug_to_console($sql);
                $stmt->execute(array($row[$pole]));
                if($sms2=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
                 //   debug_to_console($sms2[0]['kod']);
                    $mass[] =array($sms2[0]['kod'],$sms2[0]['name'],$sms2[0]['kodmenu'],$sms2[0]['kodrasdel']);
                }
            }
            return $mass;
        }

        public function buildPut($menuarr,$rasdarr){
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

        private function checkFolder($tmp,$tbl){
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
        public function makeUrl(){ //создание массива всех отображениий директорий

            $menu=$this->menu; $rasdel=$this->rasdel; $allrasdel=$this->allrasdel;
            $tmp=''; $poles=$this->polesout;
            foreach ($menu as $menrow){
                $this->makeRasdel($menrow[0],$menrow[3]);
                $tmp.='<tr><td><div class="'.$poles[4].'" id="'.$menrow[0].'_'.$menrow[3].'">'.$menrow[1].'</div></td><td>DIR</td><td></td></tr>';
            }

            $this->allmakedir['0_0']= '<table class="tblselectpunkt">'.$tmp.'</table>';

        }

        private function makeRasdel($kodmenu,$kodrasdel){ // собрать разделы в один список
            $dbh=$this->db; $mass=array();$temp=''; $return=array();
            $poles=$this->polesout;
            $mass[]='<tr><td><div class="'.$poles[4].'" id="'.'[_UPZAM]'.'">'.'...'.'</div></td><td></td><td></td></tr>';
                foreach ($this->allrasdel as $row){
                    if($row[2]==$kodmenu and $row[3]==$kodrasdel){
                        $mass[]='<tr><td><div class="'.$poles[4].'" id="'.$row[2].'_'.$row[0].'">'.$row[1].'</div></td><td>DIR</td><td><div class="buttonselectsong "></div></td></tr>';
                       // $return=array($row[2].'_'.$row[0],$row[2].'_'.$row[3]);
                        $this->makeRasdel($kodmenu,$row[0]);
                    }
                }
            $temp=$this->makePunkts($kodmenu,$kodrasdel);$return=$temp[0];
            if(strlen($temp[1])>0) {$mass[]=$temp[1]; $return=$temp[0];}
            $mass[0]=str_replace('[_UPZAM]',$return[0],$mass[0]);
                if(count($mass)>1){
                        $tmp=implode('',$mass);
                    //$this->allmakedir[]= array($return,'<table class="tblselectpunkt">'.$tmp.'</table>');
                    $this->allmakedir[$return[1]]= '<table class="tblselectpunkt">'.$tmp.'</table>';
                }
        }
        private function makePunkts($kodmenu,$kodrasdel){ //добавить пункты в раздел
            $dbh=$this->db; $mass='';$return=array();
            $tbl=$this->nametabl;
            $poles=$this->polesout;
         //   debug_to_console($kodmenu.'-'.$kodrasdel);
            foreach ($this->allrasdel as $row){
                if($row[2]==$kodmenu and $row[0]==$kodrasdel){
                    $return=array($row[2].'_'.$row[3],$row[2].'_'.$row[0]);
                }
                if(!count($return)){$return=array('0_0',$kodmenu.'_'.$kodrasdel);}
            }
            foreach ($this->rasdel as $row){
                if($row[2]==$kodmenu and $row[0]==$kodrasdel){
                    $sql='SELECT * FROM '.$tbl.' Where kodmenu='.$kodmenu.' AND kodrasdel='.$kodrasdel .' ORDER by sort' ;
                    $return=array($row[2].'_'.$row[3],$row[2].'_'.$row[0]);
                  //  debug_to_console($sql);
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
                        foreach ($sms as $row) {
                           // $mass.='<tr><td><div class="filepunkt" id="'.$row['kod'].'" title="'.$row['artist'].' - '.$row['title'].'">'.$row['artist'].' - '.$row['title'].'</div></td><td>file</td><td><div class="buttonselectsong"></div></td></td>';
                            $mass.='<tr><td><div class="filepunkt" id="'.$row['kod'].'" title="'.$row[$poles[0]].' - '.$row[$poles[1]].'">'.$row[$poles[0]].' - '.$row[$poles[1]].'</div></td><td>'.$poles[2].'</td><td><div class="'.$poles[3].'"></div></td></td>';
                        }
                    }

                }
            }
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

    public function nameTbl(){ //алиас таблицы
        include('var_alt.php');
        $this->aliastabl='';
        foreach ($massTablAlias as $key=>$value){
            if($this->nametabl==$key) {$this->aliastabl=$value;}
        }
        //debug_to_console($massTablAlias );
        return $this->aliastabl;
    }
}
?>
