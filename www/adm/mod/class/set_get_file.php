<?php

class setGetFile extends InitTable{
   private $keys;

    public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }

    public function sendAllData($field,$wheresort){
        $connect=$this->db(); $objarray=Array();
        $sql ="SELECT ".implode(",", $field)." FROM ". $this->nametabl().$wheresort;
        $stmt = $connect->prepare($sql);
        $stmt->execute();
        if($sms=$stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $record=Array();
            foreach($sms as $row){
                $objarray[]=(object)$row;
            }
        }
        return $objarray;
    }
    public function createfile($name,$json){
        file_put_contents($name, json_encode($json));
    }
    public function openfile($name){
        $file= file_get_contents($name); $fields=Array(); $keys=Array();

        if($file) {
            $obj=json_decode($file);
            foreach($obj as $row){
                $mass=(array)$row;
                $keys=array_keys($mass);
                    $fields[]=$mass;
            }
          //  debug_to_console($fields[4] );
            $this->keys=$keys;
        }
        return $fields;
    }
    public function getAllData($field,$dopkeys){
        $connect=$this->db(); $rec_keys=Array();$tmp=Array();
        if(count($this->keys)==0) {return 0;}
        foreach($this->keys as $value){
            $rec_keys[]=$value;  $tmp[]='?';
        }
        if(count($dopkeys)){
            $dopfields=array_keys($dopkeys);
            foreach($dopfields as $value){
                $rec_keys[]=$value;  $tmp[]='?';
            }
        }

        //$dopfields=implode(',',array_keys($dopkeys));
       // $dopznach=implode(',',($dopkeys));
        //if(strlen($dopfields))$dopfields=','.$dopfields;
       // if(strlen($dopznach))$dopznach=','.$dopznach;
        //debug_to_console($dopfields. $dopznach);
        foreach($field as $row) {
            $sql='INSERT INTO '. $this->nametabl().' ('.implode(',',$rec_keys).') VALUES ('.implode(',',$tmp).')';
         //   $sql = 'INSERT INTO ' . $this->nametabl() . ' (' . implode(',', $this->keys) . $dopfields.') VALUES (' . implode(',', $row) .$dopznach. ')';
            $znach=Array();
            foreach($row as $value){
                $znach[]=htmlspecialchars($value);
            }
            foreach($dopkeys as $value){
                $znach[]=($value);
            }

            //$znach= array_merge ($row,$dopkeys);
            $stmt = $connect->prepare($sql);
            $stmt->execute($znach);
           // debug_to_console($znach);
           // debug_to_console($sql);
        }
        return 1;
    }
}
?>