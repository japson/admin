<?php
class makeMenu extends createMenu{


    public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
    }

    public function makemodernMenu(){ // угловое меню

        $this->massivMenu(' WHERE vyvod=1 '); $mass=array(); $i=1;
        foreach ($this->mainmenu as $key=>$row){
            $mass[]='<div class="bumimg" id="men'.$i.'" name="'.$row['kod'].'_0_0"> 	<img src="img/klok'.$i.'.png" title="'.$row['titlepage'].'"> <div><a href="'.$this->prefix.$row['nameurl'].'" onclick="{goUrl(event);return false;}">'.$row['name'].'</a></div></div>';
            $i++;
        }
        $mass[]=' <div class="bumimg" id="menlast" name="0_0_0"> 	<img src="img/kloklast.png" title="Главная"> <div><a href="'.$this->prefix.''.'" onclick="{goUrl(event);return false;}">Меню</a></div></div>';
        $i++;

        $tmp=implode('',$mass);
        $tmp='<div class="bumenu" >'.$tmp.'</div>'; //<? echo $modern; <div class="bumcenter"></div>
        //debug_to_console($tmp);
    return $tmp;
    }

    public function makeHorMenu(){ // горизонтальное меню

        $mass=array(); $i=1;
        foreach ($this->mainmenu as $key=>$row){
            $mass[]=' <li>
                <a href="#">
                    <img src="img/2.jpg" alt=""/>
                    <span class="sdt_active"></span>
                    <span class="sdt_wrap">
							<span class="sdt_link">About me</span>
							<span class="sdt_descr">Get to know me</span>
						</span>
                </a>
            </li>';


            $mass[]='<div class="bumimg" id="men'.$i.'" name="'.$row['kod'].'_0_0"> 	<img src="img/klok'.$i.'.png" title="'.$row['titlepage'].'"> <div><a href="'.$this->prefix.$row['nameurl'].'" onclick="{goUrl(event);return false;}">'.$row['name'].'</a></div></div>';
            $i++;
        }
        $mass[]=' <div class="bumimg" id="menlast" name="0_0_0"> 	<img src="img/kloklast.png" title="Главная"> <div><a href="'.$this->prefix.''.'" onclick="{goUrl(event);return false;}">Меню</a></div></div>';
        $i++;

        $tmp=implode('',$mass);
        $tmp='<div class="bumenu" >'.$tmp.'</div>'; //<? echo $modern; <div class="bumcenter"></div>
        //debug_to_console($tmp);
        return $tmp;
    }

}
?>