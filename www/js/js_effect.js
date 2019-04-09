// JavaScript Document
$( document ).ready(function() {

    //let howlerput=function(name){if(name.substr(0,4)=='http') {return '';} else{ return '/catalog/punkts/';};}
	//console.log('test');
    $('#textarea1').niceScroll({cursorcolor:"#77262a", cursorwidth:'7'});
	loadComment(1);
	loadSongs();
    delCode();
    $(".linkpictur").fancybox({});
    //$('.comlist').niceScroll({cursorcolor:"#77262a", cursorwidth:'7'});

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

$('#menlast').mouseenter( function(event) {
    const elem=event.target||event.srcElement;
    let parent=$(elem).parents('.bumenu').css('display');
    if(parent=='grid') {
        $('.bumimg').each(function (index, element) {
            if ($(element).attr('id') != 'menlast') { $(element).addClass('povor' + index); }
        });
    }
 });

$('.bumenu').mouseleave( function() { 
$('.bumimg').each(function(index, element) {$(element).removeClass('povor'+index);});
// alert(); 
 });
    document.onkeydown = function(event) { //console.log(event.keyCode); //34 -pgdown 33-pgup

        if(event.keyCode==34 || event.keyCode==39 ){ event.preventDefault();goActPage.init(); goActPage.goPage(1); goActPage.makeUrl();}
        if(event.keyCode==33 || event.keyCode==37){ event.preventDefault();goActPage.init(); goActPage.goPage(0); goActPage.makeUrl();}
    }

    let touchStartTime;
    let touchStartLocation;
    let touchEndTime;
    let touchEndLocation;

    $(".mainpages").on('touchstart', function(event) {
        let d = new Date();
        touchStartTime = d.getTime();
        let touches = event.changedTouches;
        touchStartLocation = Array(touches[0].pageX,touches[0].pageY);//x horisont
    });

    $(".mainpages").on('touchend', function(event) {
        let d = new Date();
        touchEndTime= d.getTime();
        let touches = event.changedTouches;
        let end=touches.length;
        //  var idx = ongoingTouchIndexById(touches[i].identifier);
        touchEndLocation= Array(touches[end-1].pageX,touches[end-1].pageY);//x horisont
        doTouchLogic();
        //   console.log(idx);
        //  console.log(touchStartLocation);
//    console.log(touchEndLocation);
    });

    function doTouchLogic() {
        let distanceX = touchEndLocation[0] - touchStartLocation[0];
        let distanceY = touchEndLocation[1] - touchStartLocation[1];
        let duration = touchEndTime - touchStartTime;
        let dist=Math.abs(distanceX)-Math.abs(distanceY);
        let dolya=Math.abs(dist)-Math.abs(distanceX);
       // console.log('raznica X-Y '+dist+' :Rasstoyanie X '+(distanceX)+ 'dolya X:'+(Math.abs(dist)-Math.abs(distanceX)));
        //  return false;

        if (duration >= 150 && Math.abs(distanceX) >= 120 && Math.abs(dolya)<100) {
            if(distanceX>0){ goActPage.init(); goActPage.goPage(0); goActPage.makeUrl();}
            if(distanceX<0){ goActPage.init(); goActPage.goPage(1); goActPage.makeUrl();}
            // Person tapped their finger (do click/tap stuff here)
        }

    }


}); // $( document ).ready(function()

$( window ).resize(function() {
if(window.innerWidth >1170){$('.pen_list.maficon').css('background-image','url("/img_n/spisok_mafon.svg")');}
else{
    if($('.extra').css('display')=='none') {$('.pen_list.maficon').css('background-image','url("/img_n/spisok_mafon.svg")');} else{$('.pen_list.maficon').css('background-image','url("/img_n/spisok_mafon_activ.svg")');}
}
});


function loadComment(list){
    let rasdel=curState.getall();
    //console.log(rasdel);
   // return false;
    $.ajax({
        type: "POST", url: "/mod/comment.php", data: {listcom: list, part:rasdel.middle},
        success: function (dat) {
       //     console.log("Прибыли данные: " + dat); //+ data
            var data = JSON.parse(dat);
            if(data[0]) {
                let out = '<div class="comlist">' + data[1] + '</div>';
                $('.entercomment').html(data[2]);
                $('.listcomment').html(out);
                $('.comlist').niceScroll({cursorcolor: "#77262a", cursorwidth: '7'});
            } else{$('.entercomment').html(''); $('.listcomment').html('');}
        }
    });//ajax
}
$(document).on("click", ".answercomm", function(event){
    let elem=event.target||event.srcElement;
    $(elem).parent('.commbutton').siblings('.commaddopen').toggle('commhidden');
    $(elem).parent('.commbutton').siblings('.commaddopen').children('textarea').focus();
});
$(document).on("click", "#buttonrefr", function(event){ loadComment(1); });



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

$(document).on("click",'#buttcomment',function(){
    provCheck();
});
function provCheck() { // кнопка включения мафона
    $('.demo1').children('input').prop('checked', true);
    if($('.pen_list.comm').css('display')!='none'){
        console.log($('.extra').css('display'));
        if($('.extra').css('display')!='none') {$('.pen_list.maficon').css('background-image','url("/img_n/spisok_mafon.svg")');}else{$('.pen_list.maficon').css('background-image','url("/img_n/spisok_mafon_activ.svg")');}
        $('.extra').toggle('closed');
    }
    else {
        // $('.demo1no').children('input').prop('checked', false);
        $('.pen_list.maficon').css('background-image','url("/img_n/spisok_mafon.svg")');
        if($('.extra').css('display')!='none' && $('.comment').css('display')!='none' ){
            $('.extra').toggle('closed');
        }else{
            $('.comment').toggle('closed');  $('.extra').toggle('closed');
        }
    }
}
function provComm() { // кнопка включения comment
    $('.demo1').children('input').prop('checked', true);
    if($('.comment').css('display')!='none' ){$('.pen_list.comm').css('background-image','url("/img_n/spisok_comm.svg")');}
    else{$('.pen_list.comm').css('background-image','url("/img_n/spisok_comm_activ.svg")');}
    if($('.comment').css('display')=='none' ){$('.comment').css('display','block'); }
    else {
        $('.comment').toggle('closed');
    }

}
/*$(document).on("click",'.comlist',function(){
    $('.comlist').niceScroll({cursorcolor: "#77262a", cursorwidth: '7'});
});*/


//$('#main-container').animate(   {    width : '580px'  }, 200);
function provCheck_old22(){ // кнопка включения мафона
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
 // if (!elem2) elem2=0;
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
var middlmen=function() {
    /**
     * for each menu element, on mouseenter,
     * we enlarge the image, and show both sdt_active span and
     * sdt_wrap span. If the element has a sub menu (sdt_box),
     * then we slide it - if the element is the last one in the menu
     * we slide it to the left, otherwise to the right
     */
    $('#sdt_menu > li').bind('mouseenter',function(){
        var $elem = $(this);
        if($($elem).css('justify-self')=='baseline') {
            $elem.find('img')
                .stop(true)
                .animate({
                    'width': '134px',
                    'height': '134px',
                    'top': '0px',
                    'left': '0px'
                }, 600, 'easeOutBack')
                .addBack()
                .find('.sdt_wrap')
                .stop(true)
                .animate({'top': '175px'}, 900, 'easeOutBack')
                .addBack()
                .find('.sdt_active')
                .stop(true)
                .animate({'height': '190px'}, 500, function () {
                    var $sub_menu = $elem.find('.sdt_box');
                    if ($sub_menu.length) {
                        var left = '130px';
                        if ($elem.parent().children().length == $elem.index() + 1)
                            left = '-170px';
                        $sub_menu.show().animate({'left': left}, 250);
                    }
                });
        }
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
            .animate({'top':'17px'},500);
    });
};

//----------------------- переворот страниц

$(document).on("click",'#pusk',function(){goActPage.init(); goActPage.goPage(1); goActPage.makeUrl();});

$(document).on("click",'#downn',function(){goActPage.init(); goActPage.goPage(0); goActPage.makeUrl();});

$(document).on("click",'.nompagelink',function(event){
	let elem=event.target||event.srcElement;
	goActPage.init(); goActPage.goPage(1,$(elem).text()); goActPage.makeUrl();});

function goPageObj(){
    var listing=0; var maxPage; var curPage='page_1'; var newPage;
	var init=function()
			{var countPages=$('.mainpages').children('.secstr');
			 curPage='page_1';
                countPages.each(function(index, element) {if($(element).hasClass('current')){curPage=$(element).attr('id');}});
                curPage=curPage.replace('page_','');
                maxPage=countPages.length;
              };
	//var setNewPage=function (znach) { newPage= znach;  }
	var goPage=function(direct){ listing=1;
        if(direct==1) {newPage=parseInt(curPage,10)+1;        }
        else {newPage=parseInt(curPage,10)-1;         }
        if(arguments.length>1) newPage=arguments[1];
        if(newPage>maxPage){newPage=1;}
        if(newPage==0){newPage=maxPage;}
        var countPages=$('.mainpages');
        var newPageObj=countPages.children('.secstr[id="page_'+newPage+'"]');
       // console.log(newPageObj);
        if(newPage!=curPage) {
            newPageObj.css('display', 'block');
            curPage = countPages.children('.secstr[id="page_' + curPage + '"]');
            curPage.css('z-index', '11');
            curPage.animate({opacity: '0'}, 300);
            newPageObj.animate({opacity: '1'}, 300);
            newPageObj.addClass('current');
            curPage.removeClass('current').css('z-index', '10');
            curPage.css('display', 'none');
        }
        listing=0; $('body').animate({scrollTop: 10},300);
						}
	var makeUrl=function(){
        var path=window.location;
        var pathArray=path.toString().split('/');
		var elem_last=pathArray[pathArray.length-1];
        if(elem_last.charAt(0)=='#') pathArray.pop();
        pathArray.push('#'+newPage);
        var newhref=pathArray.join('/');
        curState.set('yakor','#'+newPage);
        history.pushState(curState.getall(), 'namepage', newhref);
        //console.log(pathArray);
	}
    var out=function() {console.log(curPage);}
	return{init:init, out:out,goPage:goPage,makeUrl:makeUrl}
}
//-------------- load songs
function loadSongs(){
    $.ajax({
        type: "POST", url: "/mod/makesong.php", data: '',
        success: function (dat) {
         //     console.log("Прибыли данные: " + dat); //+ data
            var data = JSON.parse(dat);
            let mf={songs			: data[0],songNames: data[2],sides: data[1],nomsongs:data[3],times:data[4]};
          //  console.log(mf);
            $(function() {$( '#vc-container' ).cassette(mf);    });
          //  succeed(data);
        }
    });//ajax
}

function makeCasset(param){
    $.ajax({
        type: "POST", url: "/mod/changer.php", data: {id: 3, koder: param},
        success: function (dat) {
               //  console.log("Прибыли данные: " + dat); //+ data
            var data = JSON.parse(dat);
            let mf={songs			: data[0],songNames: data[2],sides: data[1],nomsongs:data[3],times:data[4]};
           //   console.log(mf);
            let el_new = $('#vc-container').data().cassette;
            el_new._stop();
            el_new.options.songNames=mf.songNames;
            el_new.options.songs=mf.songs;
            el_new.options.nomsongs=mf.nomsongs;
            el_new.options.sides=mf.sides;  el_new.options.times=mf.times;
            // console.log(times);
            $.when( el_new._createOneSidesAll(mf.sides) ).done( function() {
                el_new._progrLoad(0); // при смене листа обязательно обновить прогресс бар
                marqueRun();
                let newSong=manageSongs;
                newSong.init();
                newSong.saveList();
                delOverley();
            });
        }
    });//ajax
}

$(document).on("click", ".savecassette", function(event){
    let tmp=$('.inputsavecass').html();
    if(tmp.length){$('.inputsavecass').html('');}
    else{$('.inputsavecass').html('Подпиши:<input type="text"><button onclick="saveCass();">Save</button>');}
});

function saveCass(){
    $.ajax({
        type: "POST", url: "/mod/savesong.php", data: {txt: $('.inputsavecass input').val()},
        success: function (dat) {
            console.log("Прибыли данные: " + dat); //+ data
            var data = JSON.parse(dat);
          //  let mf={songs			: data[0],songNames: data[2],sides: data[1],nomsongs:data[3]};
          //  console.log(mf);
         //  $(function() {$( '#vc-container' ).cassette(mf);    });
            //  succeed(data);
            $('.inputsavecass').html(data[1]);
            setTimeout(function(){$('.inputsavecass').html('');},4000);
        }
    });//ajax


}



