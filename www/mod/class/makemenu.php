<?php
class makeMenu extends createMenu{


    public function __construct($nmtbl,$dbh) {
        parent::__construct($nmtbl,$dbh);
       // $this->prefix=$_SERVER['SERVER_NAME'];
       // debug_to_console($this->prefix);
    }

    public function makemodernMenu(){ // угловое меню

        $this->massivMenu(' WHERE vyvod=1 '); $mass=array(); $i=1;
      //  debug_to_console($this->prefix);
        foreach ($this->mainmenu as $key=>$row){
            $mass[]='<div class="bumimg" id="men'.$i.'" name="'.$row['kod'].'_0_0"> 	<img src="/img/klok'.$i.'.png" title="'.$row['titlepage'].'"> <div><a href="'.$this->prefix.$row['nameurl'].'" onclick="{goUrl(event);return false;}">'.$row['name'].'</a></div></div>';
            $i++;
        }
        $mass[]=' <div class="bumimg" id="menlast" name="0_0_0"> 	<img src="/img/kloklast.png" title="Главная"> <div><a href="'.$this->prefix.''.'" onclick="{goUrl(event);return false;}">Меню</a></div></div>';
        $i++;

        $tmp=implode('',$mass);
        $tmp='<div class="bumenu" >'.$tmp.'</div>'; //<? echo $modern; <div class="bumcenter"></div>
        //debug_to_console($tmp);
    return $tmp;
    }

    public function makeHorMenu(){ // горизонтальное меню
       // debug_to_console($this->mainmenu);
        $mass=array(); $i=1; $urlmen=''; $fn='goUrl2';
        if(strlen($this->urlmenu)>0){$urlmen='/'.$this->urlmenu;$fn='goRasd';}
        foreach ($this->allrasdel as $row){
            $mass[]=' <li>
                <a class ="hmenuhref" href="'.$urlmen.'/'.$row['nameurl'].'" name="'.$row['kod'].'_'.$row['kodmenu'].'_'.$row['kodrasdel'].'" onclick="{'.$fn.'(event);;}">'.
                   ' <img src="'.$row['pictur'].'" alt=""/>
                    <span class="sdt_active"></span>
                    <span class="sdt_wrap">
							<span class="sdt_link">'.$row['name'].'</span>'.
							//<span class="sdt_descr">'.$row['titlepage'].'</span>
						'</span>
                </a>
            </li>';
        }
        $tmp=implode('',$mass);
        //debug_to_console($tmp);
        $tmp='<ul id="sdt_menu" class="sdt_menu" name="'.$this->itogname.'">'.$tmp.'</ul>'; //<? echo $modern; <div class="bumcenter"></div>
       // debug_to_console($tmp);
        return $tmp;
    }

    public function checkOpenGraph($mass){
       // debug_to_console($mass);
        if(strlen($mass['keyw'])==0){$mass['keyw']='Кривякин, Митяй, Japson, Джепсон, Искитимский андеграунд, группа из Искитима';}
        if(strlen($mass['image'])==0){$mass['image']='http://'.$_SERVER['SERVER_NAME'].'/catalog/imgnews/nopict.jpg';}
        if(strlen($mass['title'])==0){$mass['title']='Искитимский андеграунд';}
        if(strlen($mass['description'])==0){$mass['description']='Заметки, статьи участника Искитимского андеграунда папы Джепсона';}
        if(strlen($mass['url'])==0){$mass['url']='http://'.$_SERVER['SERVER_NAME'];}
        if(strlen($mass['site_name'])==0){$mass['site_name']='Japson\'s Undeground';}
     return $mass;
    }
}
?>