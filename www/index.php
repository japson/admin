<?
session_start();
//include("mod/cook_check.php");

//$sost=cook_check(0);
/*$sovok=""; $modern=""; $slide=""; $chaen=''; $piven='';
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
	}*/
$mobile='no';
require_once ('mod/mobile_detect.php');
$detect = new Mobile_Detect;
// Если мобильное устройство (телефон или планшет).
	if ( $detect->isMobile() ) {
        $mobile='yes';
	}

include('mod/create_menu.php');
include('mod/social.php');
include('mod/comment.php');


include('mod/mafon.php');
$opgraph=$page->opengraph;
//echo $_SERVER["HTTP_HOST"] ;

?>

<!DOCTYPE html>
<html>
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-124167356-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-124167356-1');
    </script>

    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link title="RSS | Japson's Undeground" type="application/rss+xml" rel="alternate" href="https://japson.ru/rss.php"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="description" content ="<? echo $opgraph["description"]; ?>">
    <meta name="keywords" content="<? echo $opgraph["keyw"]; ?>">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="<? echo $opgraph["site_name"]; ?>">
    <meta property="og:title" content="<? echo $opgraph["site_name"].": ".$opgraph["title"]; ?>">
    <meta property="og:description" content="<? echo $opgraph["description"]; ?>">
    <meta property="og:url" content="<? echo $opgraph["url"]; ?>">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:image" content="<? echo $opgraph["image"]; ?>">
    <meta property="og:image:width" content="100">
    <meta property="og:image:height" content="100">

    <script  src="https://yastatic.net/share2/share.js" ></script>

    <link rel="stylesheet" type="text/css" href="/css_n/font/comfortaa.css"/>
    <link rel="stylesheet" type="text/css" href="/css_n/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/css_n/std_menu.css"/>
    <link rel="stylesheet" type="text/css" href="/css_n/grid.css"/>
    <link rel="stylesheet" type="text/css" href="/css_n/knobKnob.css" />
    <link rel="stylesheet" type="text/css" href="/css_n/button.css"/>
    <link rel="stylesheet" type="text/css" href="/css_n/css_maf.css"/>
    <? if($mobile=='no') {echo '<link rel="stylesheet" type="text/css" href="/css_n/grid_mob.css"/>';}
    else{echo '<link rel="stylesheet" type="text/css" href="/css_n/grid_mob2.css"/>';}
    ?>

    <!--<script src="https://vk.com/js/api/openapi.js?144"></script>
    <script>
        VK.Retargeting.Init('VK-RTRG-292286-9Onx6');
    </script>-->

    <script src="/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?159",t.onload=function(){VK.Retargeting.Init("VK-RTRG-292286-9Onx6"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-292286-9Onx6" style="position:fixed; left:-999px;" alt=""/></noscript>

    <title><? echo $opgraph["site_name"].": ".$opgraph["title"]; ?></title>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '340716826697908');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=340716826697908&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->

</head>
<body >
<<script>
    fbq('track', 'ViewContent', {
        value: 1,
    });
</script>
<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
var _tmr = window._tmr || (window._tmr = []);
_tmr.push({id: "3100320", type: "pageView", start: (new Date()).getTime(), pid: "USER_ID"});
(function (d, w, id) {
  if (d.getElementById(id)) return;
  var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
  ts.src = "https://top-fwz1.mail.ru/js/code.js";
  var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
  if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window, "topmailru-code");
</script>
<!-- //Rating@Mail.ru counter -->


<div class="gridbody">
<div class="grid-element header">
          <nav class="navmenu">
              <!--<audio controls src="https://t4.bcbits.com/stream/82f74c38b1f69e430234e7de96a1cd79/mp3-128/578156894?p=0&ts=1530117850&t=3cded827d7908bff88ed532258e2f79ee3408868&token=1530117850_7a2b3ad21d8d6fde1c661b92f03648fc145999da"></audio>-->
            <? echo($menumodern); ?>
        </nav>
</div>
<div class="logo"></div>
    <div class="coffee"><div class="cofimg"><!--<img src="/img_n/kofe.png">--></div>
        <div class="cofparent"><div class="cofemenUP" id="cofemenUP"><? echo($page->cofmen); ?></div></div>
        <div class="cofemenu" id="cofemenu"><? echo($page->cofrasd); ?></div></div>

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
           <div class="prev_page" id="downn"></div>

           <div class="next_page"  id="pusk"></div>
       </div>

    </div>

</div>

    <div class="pencul">
        <div class="pensilmaf">
            <button class="pen_list comm" title="Комментарии" onclick="provComm();"></button>
            <button class="pen_list maficon" title="Магнитофон" onclick="provCheck();"></button>
        </div>
        <div class="penculinside <? if(strlen($nextart)==0){echo " hidden";} ?> ">
            <button class="pen_list vpered" title="Следующая статья" <? echo($nextart); ?> ></button>
            <button class="pen_list list" title="Вернуться к списку" <? echo($listart); ?> ></button>
            <button class="pen_list nasad" title="Предыдущая статья" <? echo($prevart); ?> ></button>
        </div>
    </div>
    <div class="social" id="social">
        <div class="ya-share2" id="share2" data-services="vkontakte,facebook,odnoklassniki,gplus,twitter,reddit,tumblr,telegram" data-counter=""></div>
    </div>
<!--<div class="grid-element main">Main Content</div>-->
    <div class="grid-element comment ">

       <!-- <div class="switch demo1 demo1no" onclick="provCheck();"> <input type="checkbox" ><label></label></div>-->
         <div class="comminside">
             <div class=""><!--<button onclick="ExitSocial()">exit</button>-->
            <!-- <script src="//ulogin.ru/js/ulogin2.js"></script>-->
          <div class="socialstatus">
           <? echo $outssesion;  ?>
          </div>
           <div class="entercomment">
              <!-- < ? echo $outcomment; ?>-->
           </div>
             <div class="listcomment">

             </div>
    </div>
         </div>
        <!-- Yandex.Metrika counter -->
        <script type="text/javascript" >
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter50011537 = new Ya.Metrika2({
                            id:50011537,
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true
                        });
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/tag.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks2");
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/50011537" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->
         </div> <!--comment-->
<div class="grid-element extra closed">
    <div id="main-container" class="main-container">

        <div class="switch demo1" onclick="provCheck();"> <input type="checkbox" ><label></label></div>
        <? echo $mafon; ?>
    </div>

</div>

</div>
<!--<div class="grid-element footer">Footer</div>-->


<script type="text/javascript" src="/js/transform.js"></script>
<script type="text/javascript" src="/js/knobKnob.jquery.js"></script>
<script type="text/javascript" src="/js/jquery.cassette.js"></script>
<script type="text/javascript" src="/js/container.js"></script>
<script type="text/javascript" src="/js/jquery.simplemarquee.js"></script>
<script type="text/javascript" src="/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="/js/jquery.nicescroll.min.js"></script>
<link rel="stylesheet" type="text/css" href="/fancy/jquery.fancybox.min.css">
<script src="/fancy/jquery.fancybox.min.js"></script>

<script src="/js/howler.core.js"></script>
<script src="/js/js_effect.js"></script>
<script src="/js/js.js"></script>

</body>
</html>