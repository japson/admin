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
include('/mod/create_menu.php');
include('/mod/mafon.php');
//echo $_SERVER["HTTP_HOST"] ;

?>

<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="stylesheet" type="text/css" href="/css/css_eff.css"/>
    <link rel="stylesheet" type="text/css" href="/css/css.css"/>
    <link rel="stylesheet" type="text/css" href="/css/css_maf.css"/>

    <script src="/js/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/css/animbkv/normalize.css" />
		<!--<link rel="stylesheet" type="text/css" href="css/animbkv/component.css" />-->
		<script src="/css/animbkv/modernizr.custom.js"></script>


<link rel="stylesheet" type="text/css" href="/css/knobKnob.css" />

<link rel="stylesheet" type="text/css" href="/css/button.css"/>
<link rel="stylesheet" type="text/css" href="/css/std_menu.css"/>
<link href="/css/fonts/comfortaa.css" rel="stylesheet"/>




<title>Документ без названия</title>

</head>
<body class="bodpivo">
<ul class="cb-slideshow new" <? echo $slide; ?>>
   <!-- <li> <span>Image 01</span></li>
    <li> <span>Image 021</span></li>-->
</ul>
<div style=" width:100%; height:1px; clear:both;"> </div>
<div class='leftblock '>  
			

    <nav>
    <? echo($menumodern); ?>
    </nav>
	<div class='pivo'>
	<div class=" piven piventer <? echo $piven; ?> "> 	</div>
    <div class="piven piventer2 <? echo $piven; ?>"> 	</div>
    <div class=' <? echo $classpiv; ?>'> 	<img src='/img/pivo.png'> 	</div>
    <div class='<? echo $classchai; ?>'> 	<img src='/img/chai.png'> 	</div>
    <div class="chaien chaienter2 <? echo $chaen; ?>"> 	</div>
    <div class="chaien chaienter <? echo $chaen; ?>"> 	</div>
	
	</div>
   
</div>

<div class='midblock '>
    <div class="beforemidmenu">
    <div class="middlemenu" id="middle_menu">
        <? echo($menuhoriz); ?>

    </div>
    <!--<div class="middlemenuafter"></div>-->
    </div>
		<div class='pagecurrent'> <img class="blocknot" src="/img/blocknot2.png">
        
       			<div id="mainpages" class="mainpages">
                    <? echo($massart[0]); ?>

                        
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

<script type="text/javascript" src="/js/transform.js"></script>
<script type="text/javascript" src="/js/knobKnob.jquery.js"></script>
<script type="text/javascript" src="/js/jquery.cassette.js"></script>
<script type="text/javascript" src="/js/container.js"></script>
<script type="text/javascript" src="/js/jquery.simplemarquee.js"></script>
<script type="text/javascript" src="/js/jquery.easing.1.3.js"></script>
<script src="/js/js_effect.js"></script>
<script src="/js/js.js"></script>
</body>
</html>
