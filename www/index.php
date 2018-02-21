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
$opgraph=$page->opengraph;
//echo $_SERVER["HTTP_HOST"] ;

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="keywords" content="<? echo $opgraph['keyw']; ?>">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="<? echo $opgraph['site_name']; ?>">
    <meta property="og:title" content="<? echo $opgraph['site_name'].': '.$opgraph['title']; ?>">
    <meta property="og:description" content="<? echo $opgraph['description']; ?>">
    <meta property="og:url" content="<? echo $opgraph['url']; ?>">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:image" content="<? echo $opgraph['image']; ?>">
    <meta property="og:image:width" content="100">
    <meta property="og:image:height" content="100">

    <link rel="stylesheet" type="text/css" href="/css_n/font/comfortaa.css"/>
    <link rel="stylesheet" type="text/css" href="/css_n/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/css_n/std_menu.css"/>
    <link rel="stylesheet" type="text/css" href="/css_n/grid.css"/>
    <link rel="stylesheet" type="text/css" href="/css_n/knobKnob.css" />
    <link rel="stylesheet" type="text/css" href="/css_n/button.css"/>
    <link rel="stylesheet" type="text/css" href="/css_n/css_maf.css"/>

    <script src="/js/jquery-3.2.1.min.js"></script>

    <title><? echo $opgraph['site_name'].': '.$opgraph['title']; ?></title>
</head>
<body class="gridbody">
<div class="grid-element header">
          <nav class="navmenu">
            <? echo($menumodern); ?>
        </nav>

    <div class="logo"></div>
    <div class="coffee"><div class="cofimg"><img src="/img_n/kofe.png"></div>
        <div class="cofparent"><div class="cofemenUP" id="cofemenUP"><? echo($page->cofmen); ?></div></div>
        <div class="cofemenu" id="cofemenu"><? echo($page->cofrasd); ?></div></div>
</div>
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
<div class="grid-element extra">
    <div id="main-container" class="main-container">
        <div class="switch demo1" onclick="provCheck();"> <input type="checkbox" ><label></label></div>
        <? echo $mafon; ?>
    </div>
</div>

<!--<div class="grid-element footer">Footer</div>-->
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