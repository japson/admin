// JavaScript Document
$( document ).ready(function() {
	console.log('test');
//$('.piven').each(function(index, element) {$(element).addClass('cursor');   });	
	
$(document).on("click",'.piven',function(){
	if($('div').hasClass('pivo_rot')) {}
	else {
		$('.pivoimg').addClass('pivo_rot').removeClass('pivoimg');
		$('.chaiimg').addClass('chai_rot').removeClass('chaiimg');
		$('.piven').each(function(index, element) {$(element).removeClass('cursor');   });
		$('.chaien').each(function(index, element) {$(element).addClass('cursor');   });
		$('.cb-slideshow').animate({opacity:'0'},900, function(){
	//$('.cb-slideshow').css("background-image",'url("../img/kultura_empty.jpg")');	
	//$('.cb-slideshow').animate({opacity:'1'},600);
		});
		//$('.bumenu').css('opacity',0).css('display','block');
	//	$('.bumenu').animate({opacity:'1'},700,function(){});
	//	$('.menu').animate({opacity:'0'},700,function(){$('.menu').css('display','none');});
		savcook(2);
	}
	//tango(0); return false;
			});

$(document).on("click",'.chaien',function(){
	if($('div').hasClass('chai_rot')) {
		$('.pivo_rot').addClass('pivoimg').removeClass('pivo_rot');
		$('.chai_rot').addClass('chaiimg').removeClass('chai_rot');
		$('.chaien').each(function(index, element) {$(element).removeClass('cursor');   });
		$('.piven').each(function(index, element) {$(element).addClass('cursor');   });
		$('.cb-slideshow').animate({opacity:'1'},900, function(){	});
	//	$('.menu').css('opacity',0).css('display','block');
	//	$('.menu').animate({opacity:'1'},700,function(){});
		//$('.bumenu').animate({opacity:'0'},700,function(){$('.bumenu').css('display','none');});
		savcook(1);
		}else {}
	
	// tango(1); return false;
	});	

function savcook(perem) {
	$.ajax({
    type: "POST",   url: "mod/cook_sav.php",   data: 'data='+perem,
  success: function(data){
	 // alert( "Прибыли данные: " + data );
	 	var mass=JSON.parse(data); 
		if (mass[0]['atribut']==0) {alert (mass[0]['text'] );}
		if (mass[0]['atribut']==1) {}
						  }     });	

}

$('#menlast').mouseenter( function() {
$('.bumimg').each(function(index, element) {if($(element).attr('id')!='menlast'){$(element).addClass('povor'+index);}});
//$('.bumimg').children('div').each(function(index, element) {$(element).addClass('cursor');});
// alert(); 
 });
$('.bumenu').mouseleave( function() { 
$('.bumimg').each(function(index, element) {$(element).removeClass('povor'+index);});
// alert(); 
 }); 




}); // $( document ).ready(function() {



tango=function (nope){
	
	if(nope==1){var thikl='visible'; moto='hidden';
	//$('.pivo_rot').children('img').attr('src','img/pivo.png');
	$('.pivoimg').children('img').animate({src:'img/pivo.png'},1000);
	//alert();
	//$('.pivoimg').children('img').attr('src','img/pivo.png');
	//$('.pivoimg').children('img').addClass('pivoimg');
	}
	else{thikl='hidden'; moto='visible';
	$('.pivoimg').children('img').removeClass('pivoimg');
	$('.pivo_rot').children('img').attr('src','img/chai.png');}
	$('.piventer').css('visibility',thikl);
	$('.piventer2').css('visibility',thikl);
	$('.chaienter').css('visibility',moto);
	$('.chaienter2').css('visibility',moto);
	};			
	
// магнитофон ------------------------------------------------------------------------------------------
function marqueRun(){ //Запуск скоролинга
	/*$('.marque').on({
                'cycle': console.log.bind(console, 'example-left', 'cycle'),
                'pause': console.log.bind(console, 'example-left', 'pause'),
                'resume': console.log.bind(console, 'example-left', 'resume'),
                'finish': console.log.bind(console, 'example-left', 'finish')
            });*/
            $('.marque').simplemarquee();	
}

function provCheck(){ // кнопка включения мафона
var per=$( '.switch.demo1' ).find('input:checked').length;
if (per==1){ $('#main-container').animate(   {    width : '580px'  }, 2000);
//$('#vc-container').removeClass('vc-cont-hid');
} else {
$('#main-container').animate(   {    width : '150px'  }, 2000);
//$('.vc-tape').animate(   {    opacity : '0'  }, 2000);
//$('#vc-container').addClass('vc-cont-hid');
}
console.log(per);
}

function beginSong(event){  // перемотка на начало песни
	var elem = event.target || event.srcElement;
  var elem2 = $(elem).siblings('.tablbar').find('.progrbar').attr('id');
 // console.log(elem2);
	var el=$('#vc-container');
	var msSide=el.data().cassette._getSide().current; //id: "side2", status: "middle", playlist: Array[2], duration: 433.781932, playlistCount: 2 } playlistCount список песен  
	var masSong=Array();
	for(var i=0;i<msSide.playlist.length; i++) {
		masSong[i]=msSide.playlist[i].duration;
		}
	var tek_vrem=el.data().cassette.cntTime; // текущее время на стороне кассеты
	var msTekPos=el.data().cassette._getSongInfoByTime(tek_vrem);//  текущая песня/Object { songIdx: 0, timeInSong: 13.882392, iterator: 0 } iterator - сумма время предыдущих песен 
	i=0; var timcur=0;
//return false;
	while(elem2>i) {
		timcur+=masSong[i];
		i++;
		}
	elem=el.data().cassette;
	elem._stop();
	elem._progrLoad(0);
	setTimeout( function() {
			elem.cntTime = timcur;//_self._getPosTime();
			 wheelVal2	= elem._getWheelValues( elem.cntTime );
			elem._updateWheelValue( wheelVal2 );
			elem._play();
			elem._progrLoad(1);
			}, 200 );
	//console.log(elem);
	
	}
	//------------------среднее меню
$(function() {
    /**
     * for each menu element, on mouseenter,
     * we enlarge the image, and show both sdt_active span and
     * sdt_wrap span. If the element has a sub menu (sdt_box),
     * then we slide it - if the element is the last one in the menu
     * we slide it to the left, otherwise to the right
     */
    $('#sdt_menu > li').bind('mouseenter',function(){
        var $elem = $(this);
        $elem.find('img')
            .stop(true)
            .animate({
                'width':'130px',
                'height':'130px',
                'top':'0px',
                'left':'0px'
            },600,'easeOutBack')
            .addBack()
            .find('.sdt_wrap')
            .stop(true)
            .animate({'top':'170px'},900,'easeOutBack')
            .addBack()
            .find('.sdt_active')
            .stop(true)
            .animate({'height':'170px'},500,function(){
                var $sub_menu = $elem.find('.sdt_box');
                if($sub_menu.length){
                    var left = '130px';
                    if($elem.parent().children().length == $elem.index()+1)
                        left = '-170px';
                    $sub_menu.show().animate({'left':left},250);
                }
            });
    }).bind('mouseleave',function(){
        var $elem = $(this);
        var $sub_menu = $elem.find('.sdt_box');
        if($sub_menu.length)
            $sub_menu.hide().css('left','0px');

        $elem.find('.sdt_active')
            .stop(true)
            .animate({'height':'0px'},300)
            .addBack().find('img')
            .stop(true)
            .animate({
                'width':'0px',
                'height':'0px',
                'left':'85px'},400)
            .addBack()
            .find('.sdt_wrap')
            .stop(true)
            .animate({'top':'25px'},500);
    });
});

//----------------------- переворот страниц
var listing=0;
        $(document).on("click",'#pusk',function(){
			var countPages=$('.mainpages').children('.secstr');
			var curPage='page1';
			countPages.each(function(index, element) {if($(element).hasClass('current')){curPage=$(element).attr('id');}});
			curPage=curPage.replace('page','');
			maxPage=countPages.length;
			//console.log(curPage);
			//console.log(countPages);
			if(listing==0) {goPage(curPage,maxPage,1)}
			});
			
		$(document).on("click",'#downn',function(){
			var countPages=$('.mainpages').children('.secstr');
			var curPage='page1';
			countPages.each(function(index, element) {if($(element).hasClass('current')){curPage=$(element).attr('id');}});
			curPage=curPage.replace('page','');
			maxPage=countPages.length;
			//console.log(listing);
			//console.log(countPages);
			if(listing==0) {goPage(curPage,maxPage,0)}
			});	
			
var goPage=function(curPage,maxPage,direct){
	var scrol=$('body').css('overflow-y');
	//console.log(document.documentElement.clientHeight); console.log(document.documentElement.scrollHeight);
	if(document.documentElement.clientHeight >= document.documentElement.scrollHeight)
	 {$('body').css('overflow-y','hidden');}
	listing=1;
	if(direct==1) {
		var newPage=parseInt(curPage,10)+1;
		var trans='transfU';
		var anim='spin2';
				}
		else {var newPage=parseInt(curPage,10)-1;
		var trans='transfD';
		var anim='spin3';
		}
		if(newPage>maxPage){newPage=1;}
		if(newPage==0){newPage=maxPage;}
		var countPages=$('.mainpages');
		
		
		newPage=countPages.children('.secstr[id="page'+newPage+'"]');
		curPage=countPages.children('.secstr[id="page'+curPage+'"]');
		
if(direct==1) {			
	curPage.css('z-index','11').addClass(trans);
	newPage.css('display','block');
	curPage.addClass(anim).animate(   {    opacity : '0'  }, 1700);
	newPage.animate(   {    opacity : '1'  }, 200);
	setTimeout(function() { curPage.css("display","none") 
	newPage.addClass('current');
	curPage.removeClass('current').removeClass(trans).removeClass(anim).css('z-index','10');;
	listing=0;
	$('body').css('overflow-y',scrol);
	}, 1500);
} else {
	newPage.css('display','block').css('z-index','11');
	newPage.addClass('transfD');
	newPage.addClass(anim).animate(   {    opacity : '1'  }, 1000);
	//curPage.animate(   {    opacity : '0'  }, 3000);
	setTimeout(function() { curPage.css("display","none"); 
	newPage.addClass('current').removeClass(trans).removeClass(anim).css('z-index','10');
	curPage.removeClass('current').css('opacity','0');
	listing=0;
	$('body').css('overflow-y',scrol);
	}, 1500);
}
	
	
	//console.log(newPage);
	
	}	   	