<?php
class dirPunkt{
    private $db;
    private $nametabl;
    private $maintable;
    private $menutable;

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
