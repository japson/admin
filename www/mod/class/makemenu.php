<?php
class makeMenu extends createMenu{


    public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }

    public function makemodernMenu(){

        $this->massivMenu(); $mass=array(); $i=1;
        foreach ($this->mainmenu as $key=>$row){
            $mass[]='<div class="bumimg" id="men'.$i.'" name="'.$row['kod'].'_0_0"> 	<img src="img/klok'.$i.'.png" title="'.$row['titlepage'].'"> <div><a href="'.$this->prefix.$row['nameurl'].'" onclick="{goUrl();return false;}">'.$row['name'].'</a></div></div>';
            $i++;
        }
        $mass[]=' <div class="bumimg" id="menlast" name=""> 	<img src="img/kloklast.png" title="Главная"> <div><a href="'.$this->prefix.''.'" onclick="{goUrl();return false;}">Меню</a></div></div>';
        $i++;

        $tmp=implode('',$mass);
        $tmp='<div class="bumenu" >'.$tmp.'</div>'; //<? echo $modern; <div class="bumcenter"></div>
        //debug_to_console($tmp);
    return $tmp;
    }

}
?>