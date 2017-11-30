<?
include("mod/cook_check.php");
$sost=cook_check(0);
$sovok=""; $modern=""; $slide=""; $chaen=''; $piven='';
if ($sost==1) {
	$sovok="style='opacity: 1; display: block;'";
	$slide="style='opacity: 1;'";
	$classpiv='pivoimg';
	$classchai='chaiimg';
	$piven=" cursor";

	}
else  {
	$modern="style='opacity: 1; display: block;'";
	$slide="style='opacity: 0;'";
	$classpiv='pivoimg pivo_rot';
	$classchai='chaiimg chai_rot';
	$chaen=" cursor";
	}
include('mod/mafon.php');
//echo $_SERVER["HTTP_HOST"] ;
include('mod/create_menu.php');
?>

<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

<script src="js/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/animbkv/normalize.css" />
		<!--<link rel="stylesheet" type="text/css" href="css/animbkv/component.css" />-->
		<script src="css/animbkv/modernizr.custom.js"></script>

<link rel="stylesheet" type="text/css" href="css/css_eff.css"/>
<link rel="stylesheet" type="text/css" href="css/knobKnob.css" />
<link rel="stylesheet" type="text/css" href="css/css_maf.css"/>
<link rel="stylesheet" type="text/css" href="css/button.css"/>
<link rel="stylesheet" type="text/css" href="css/std_menu.css"/>
    <link rel="stylesheet" type="text/css" href="css/css.css"/>

<link href="css/fonts/comfortaa.css" rel="stylesheet"/>




<title>Документ без названия</title>

</head>
<? debug_to_console($menumodern); ?>
<body class="bodpivo">
<ul class="cb-slideshow new" <? echo $slide; ?>>
    <li> <span>Image 01</span></li>
    <li> <span>Image 021</span></li>
</ul>
<div style=" width:100%; height:1px; clear:both;"> </div>
<div class='leftblock '>  
			
            <!--<div class="menu "<?/* echo $sovok;  */?> >
				<ul class="grid">
					<li class="ot-letter-bottom"><span data-letter="Партия">Статьи</span></li>
                    <li class="ot-letter-bottom"><span data-letter=" – Наш">&nbsp;&nbsp;Фото&nbsp;</span></li>
                     <li class="ot-letter-bottom"><span data-letter="Рулевой!">Музыка</span></li>

                   </ul>
            </div>
           
            <div class="menu menu2"<?/* echo $sovok; */?> >
                   <ul class="grid">
					<li class="ot-letter-bottom"><span data-letter="Космос">Стихи&nbsp;</span></li>
                    <li class="ot-letter-bottom"><span data-letter=" – Детям!">&nbsp;&nbsp;&nbsp;Japson&nbsp;</span></li>
                     

                   </ul>
			</div>-->
            
            <!--<div class='bumenu' <?/* echo $modern; */?>>
             <div class='bumimg' id="men1"> 	<img src='img/klok1.png'> <div>Музыка</div></div>
            <div class='bumimg'  id="men2"> 	<img src='img/klok2.png'><div>Стихи</div> 	</div>
             <div class='bumimg'  id="men3"> 	<img src='img/klok3.png'> <div>Фото</div>	</div>
             <div class='bumimg'  id="men4"> 	<img src='img/klok4.png'> <div>Статьи</div>	</div>
             <div class='bumimg'  id="men5"> 	<img src='img/klok5.png'> <div>Japson</div>	</div>
             <div class='bumimg'  id="men6"> 	<img src='img/klok6.png'> <div>Меню</div>	</div>
              <div class='bumcenter'> </div>
            </div>-->
    <? echo($menumodern); ?>
                     
	<div class='pivo'>
	<div class=" piven piventer <? echo $piven; ?> "> 	</div>
    <div class="piven piventer2 <? echo $piven; ?>"> 	</div>
    <div class=' <? echo $classpiv; ?>'> 	<img src='img/pivo.png'> 	</div>
    <div class='<? echo $classchai; ?>'> 	<img src='img/chai.png'> 	</div>
    <div class="chaien chaienter2 <? echo $chaen; ?>"> 	</div>
    <div class="chaien chaienter <? echo $chaen; ?>"> 	</div>
	
	</div>
   
</div>

<div class='midblock '>
    <div class="beforemidmenu">
    <div class="middlemenu">
        <ul id="sdt_menu" class="sdt_menu">
            <li>
                <a href="#">
                    <img src="img/2.jpg" alt=""/>
                    <span class="sdt_active"></span>
                    <span class="sdt_wrap">
							<span class="sdt_link">About me</span>
							<span class="sdt_descr">Get to know me</span>
						</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="img/1.jpg" alt=""/>
                    <span class="sdt_active"></span>
                    <span class="sdt_wrap">
							<span class="sdt_link">Portfolio</span>
							<span class="sdt_descr">My work</span>
						</span>
                </a>
                <div class="sdt_box">
                    <a href="#">Websites</a>
                    <a href="#">Illustrations</a>
                    <a href="#">Photography</a>
                </div>
            </li>
            <li>
                <a href="#">
                    <img src="img/1.jpg" alt=""/>
                    <span class="sdt_active"></span>
                    <span class="sdt_wrap">
							<span class="sdt_link">Portfolio</span>
							<span class="sdt_descr">My work</span>
						</span>
                </a>
                <div class="sdt_box">
                    <a href="#">Websites</a>
                    <a href="#">Illustrations</a>
                    <a href="#">Photography</a>
                </div>
            </li>
           <!-- <li class="toendli"><div class="toend"></div></li>-->
        </ul>

    </div>
    <!--<div class="middlemenuafter"></div>-->
    </div>
		<div class='pagecurrent'> <img class="blocknot" src="img/blocknot2.png">
        
       			<div id="mainpages" class="mainpages">
                 		<div class='secstr current ' id='page1'>
                        <img class="blokpage" src="img/blokpage.jpg">
                        <div class='txt_block'> fsdfsdfsd <br> rterterterfsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br></div>
                        </div>
                        
                       <div class="secstr" id='page2'>
                        <img class="blokpage" src="img/blokpage.jpg">
                        <div class='txt_block'> fsdfsdfsd <br> rterterterfsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdf453453453453453453453453354sdfsd <br></div>
                        
                        </div>
                       
                       <div class="secstr" id='page3'>
                        <img class="blokpage" src="img/blokpage.jpg">
                        <div class='txt_block'> fsdfsdfsd <br> rterterterfsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdasdasdaaaaaaaaaaaaaaaaaaaaaaaaaaafsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdfsdfsd <br>fsdf453453453453453453453453354sdfsd <br></div>
                        
                        </div> 
                        
        		</div>
<div class='prev_page' id="downn"></div>           <div class='next_page'  id="pusk"></div>        
        </div> <!--pagecurrent-->
        
</div> <!--midblock-->

<div class='rightblock '> 
<div id="main-container" class="main-container">
<div class="switch demo1" onclick="provCheck();"> <input type="checkbox" ><label></label></div>
<? echo $mafon; ?>
</div>   
</div>

<div style=" width:100%; height:1px; clear:both;"> </div>


<div class="___container">
			
			
</div><!-- /container -->
<!--bodpivo -->

<script type="text/javascript" src="js/transform.js"></script>
<script type="text/javascript" src="js/knobKnob.jquery.js"></script>
<script type="text/javascript" src="js/jquery.cassette.js"></script>
<script type="text/javascript" src="js/container.js"></script>
<script type="text/javascript" src="js/jquery.simplemarquee.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script src="js/js_effect.js"></script>
</body>
</html>
