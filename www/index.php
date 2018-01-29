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
    <link rel="stylesheet" type="text/css" href="/css_n/font/comfortaa.css"/>
    <link rel="stylesheet" type="text/css" href="/css_n/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/css_n/std_menu.css"/>
    <link rel="stylesheet" type="text/css" href="/css_n/grid.css"/>

    <script src="/js/jquery-3.2.1.min.js"></script>

    <title>Документ без названия</title>
</head>
<body class="gridbody">
<div class="grid-element header">
           <nav class="navmenu">
            <? echo($menumodern); ?>
        </nav>
</div>
<div class="coffee"><div class="cofimg"><img src="/img_n/kofe.png"></div> <div class="cofemenUP" id="cofemenUP"><? echo($page->cofmen); ?></div><div class="cofemenu" id="cofemenu"><? echo($page->cofrasd); ?></div></div>
<div class="grid-element main">

    <div class="main_grid">
        <div class="beforemidmenu">
        <div class="middlemenu" id="middle_menu">
            <? echo($menuhoriz); ?>
        </div>
        </div>
        <div class="podfon">     </div>
        <div class="podfon_paper">   </div>
        <div class="podfon_paper_txt">  </div>
        <div class="podfon_paper_down">    </div>
        <div id="mainpages" class="mainpages">
            <? echo($massart[0]); ?></div>
       <div class="pages_go">
           <div class='prev_page' id="downn"></div>           <div class='next_page'  id="pusk"></div>
       </div>
    </div>
</div>
<!--<div class="grid-element main">Main Content</div>-->
<div class="grid-element extra">Extra Info</div>

<!--<div class="grid-element footer">Footer</div>-->
<script type="text/javascript" src="/js/transform.js"></script>
<!--<script type="text/javascript" src="/js/knobKnob.jquery.js"></script>-->
<script type="text/javascript" src="/js/jquery.cassette.js"></script>
<!--<script type="text/javascript" src="/js/container.js"></script>-->
<!--<script type="text/javascript" src="/js/jquery.simplemarquee.js"></script>-->
<script type="text/javascript" src="/js/jquery.easing.1.3.js"></script>
<script src="/js/js_effect.js"></script>
<script src="/js/js.js"></script>
</body>
</html>