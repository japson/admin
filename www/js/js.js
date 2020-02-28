/**
 * Created by RARETA on 05.12.2017.
 */
socialButton();
var curState=currentState();
middlmen();
var goActPage=goPageObj();
initcurState();

function currentState(){
    var state={menu:'',middle:'',yakor:'',right:'',left:'',cofmen:'',cofrasd:''};
    var set=function(name,val){state[name]=val; };
    var get=function(name){return state[name];};
    var getall=function(){return state;};
    return {set:set, get:get,getall:getall};
}

//document.getElementById('social').innerHTML = VK.Share.button({noparse :false},{type: 'round',text : 'Поделиться' });
function socialButton() {
        Ya.share2('share2', {
        content: {
            url: document.head.querySelector("[property='og:url']").content,
            title: document.head.querySelector("[property='og:title']").content,
            description: document.head.querySelector("[property='og:description']").content,
            image: document.head.querySelector("[property='og:image']").content
        },
            theme: {
                direction: 'vertical',
                bare: false,
                size: 'm',

            }
    });
}

function noner(){return false;}

//console.log(curState.getall());

var coffUrl=function(menu,rasdel){
    if(menu) $('#cofemenUP').text(menu.toUpperCase());
    if(rasdel) {$('#cofemenu').text(rasdel.toUpperCase());}
    else {$('#cofemenu').text('');}
}
    function goUrl(event){
        event.preventDefault();
        var elem=event.target||event.srcElement;
        var men=$(elem).closest('div').parent('div').attr('name');
        var newhref=$(elem).attr('href');
        goUrlmade(men,newhref,$(elem).text());
        elem.blur(); // убрать фокус
    }
function goUrl2(event){
    event.preventDefault();
    var elem=event.target||event.srcElement;
    var men=$(elem).closest('a').attr('name');
    var newhref=$(elem).closest('a').attr('href');
   // console.log($(elem).closest('a').find('.sdt_link').text());
    goUrlmade(men,newhref,$(elem).closest('a').find('.sdt_link').text());
}

    function goUrlmade(men,newhref,txt){ // главное
   // event.preventDefault();
   // var elem=event.target||event.srcElement;
   // var men=$(elem).closest('div').parent('div').attr('name');
    var mass=men.split('_');
    var newmas=[mass[0],'',mass[1],mass[2],'hm'];
    //var path=e.target.location.pathname.substr(1);
    // console.log('::'+decodeURIComponent(path));
   // path=decodeURIComponent(path);
   // var newhref=$(elem).attr('href');
    makerMenu(newmas,'middle_menu',men,middlmen,newhref);
    coffUrl(txt,'');
  //  middlmen();
   // console.log(window.history.state);
    curState.set('cofmen',txt);
    curState.set('cofrasd','');
    curState.set('menu',men);
    curState.set('middle',men);
    curState.set('yakor','#1');
   // console.log(newhref);
    history.pushState(curState.getall(), 'namepage', newhref);
   // console.log(window.location.toString());

}
//$(document).on("click", "a.hmenuhref", function(event){
   function goRasd(event) {let elem=event.target||event.srcElement;
       event.preventDefault();
    let men=$(elem).closest('a').attr('name');
    let newhref=$(elem).closest('a').attr('href');
       goLinkRasd(men,newhref,'#1');
       let urlrasd=$(elem).closest('a').find('.sdt_link').text();
       coffUrl('',urlrasd);
       curState.set('cofrasd',urlrasd);
}
$(document).on("click", ".pen_list.list", function(event){
    let elem=event.target||event.srcElement;
    let men=$(elem).attr('name');
    let newhref=$(elem).attr('dathref');
    const pageyak=newhref.split('/#');
    //pageyak='#'+pageyak[1] || 1;

            goLinkRasd(men,newhref,'#'+pageyak[1] || 1);

      //  console.log(newhref);
        goActPage.init();goActPage.goPage(1,'#'+pageyak[1] || 1);

});

function goLinkRasd(men,newhref,pageyak){
    let mass=men.split('_');
    let newmas=[mass[0],'',mass[1],mass[2],'ra'];
    // history.replaceState(curState.getall(), 'namepage2', '');
    curState.set('middle',men);
    curState.set('yakor',pageyak);
    makerMenu(newmas, 'mainpages', men, noner, newhref,pageyak);

    let peremsost=curState.getall(); history.pushState(peremsost, null, newhref);


    // history.replaceState(peremsost, null, window.location);

}

$(document).on("click", "a.linkarticle", function(event){
    let elem=event.target||event.srcElement;
    let men=$(elem).closest('tr').attr('name');
    let newhref=$(elem).closest('a').attr('href');
    goLinkArt(men,newhref);
    return false;
});

$(document).on("click", ".pen_list.vpered", function(event){
    innerClick(event.target||event.srcElement,'dathref');
    return false;
});

$(document).on("click", ".pen_list.nasad", function(event){
    innerClick(event.target||event.srcElement,'dathref');
    return false;
});
function innerGo(event){
    innerClick(event.target||event.srcElement,'href');
    return false;
}
function innerClick(elem,atr){
    let men=$(elem).attr('name');
    let newhref=$(elem).attr(atr);
    goLinkArt(men,newhref);
}
function goLinkArt(men,newhref){
    let mass=men.split('_');
    let newmas=[mass[0],'',mass[1],mass[2],'oa'];
    //  console.log(newhref);
    curState.set('middle',men);
    curState.set('yakor','#1');
    makerMenu(newmas,'mainpages',men,noner,newhref);
    let peremsost=curState.getall();
    history.pushState(peremsost, null, newhref);
}



function makerMenu(mass,id,men,callback,newhref,yakor){
    var data3={key:mass[0],tbl:mass[1],km:mass[2],kr:mass[3],what:mass[4],href:newhref};
    var path=window.location;
    let urlold=document.querySelector('meta[property="og:url"]').getAttribute('content');
   // console.log (data3);
    $.ajax({
        type: "POST",   url: "/mod/hormenu.php",   data: data3,
        success: function(dat){
          //console.log( "Прибыли данные: " + dat  ); //+ data
            var data= JSON.parse(dat);
         //   console.log('**'+mass[4]);
          //  console.log('**'+data[1][0]);
            if(mass[4]=='hm' || mass[4]=='hmr') $('#'+id).html(data[0]);
            if(mass[4]=='hm') $('#mainpages').html(data[1][0]).attr('name',men);
            if(mass[4]=='ma') {$('#mainpages').html(data[1][0]).attr('name',men); $('.penculinside').addClass('hidden').children().removeAttr('name').removeAttr('dathref');}
            if(mass[4]=='ra') {$('#mainpages').html(data[1][0]).attr('name',men); $('.penculinside').addClass('hidden').children().removeAttr('name').removeAttr('dathref');}
            if(mass[4]=='oa') {$('#mainpages').html(data[1][0]).attr('name',men); goArticle(data[2],data[3],data[4]);$('.penculinside').removeClass('hidden');}

            if(yakor){myYakor(yakor);}
           // console.log(mass[4]);
           // console.log(data[1][1]);
           if(mass[4]!='hmr') meta(data[1][1]);
            socialButton();
            callback();
            loadComment(1);
             madeYandex(urlold,path);
            return true;
          //  console.log(curState.getall());
            //history.pushState(curState.getall(), 'namepage', newhref);
        }// success
    });
        /*complete: function(jqXHR, textStatus){
            console.log(textStatus);
            console.log(jqXHR);
        }*/


    var meta=function(data){
     //   console.log(data);
        document.querySelector('meta[property="og:description"]').setAttribute("content", data['description']);
        document.querySelector('meta[property="og:site_name"]').setAttribute("content", data['site_name']);
        document.querySelector('meta[property="og:title"]').setAttribute("content", data['site_name']+': '+data['title']);
        document.querySelector('meta[property="og:image"]').setAttribute("content", data['image']);
        document.querySelector('meta[property="og:url"]').setAttribute("content", data['url']);
        document.querySelector('meta[name="keywords"]').setAttribute("content", data['keyw']);
        document.title = data['site_name']+': '+data['title'];
       // console.log('-----');
    }

   // console.log(curState.getall());
}
function madeYandex(urlold,path){
    //console.log(newhref); 50011537
    var shablon='crazys/photo';
    //var shablon='https://japson.ru/catalog/imgnews/';
    var fullUrl=path.toString();
    let nofoto=fullUrl.indexOf(shablon)+1*1;
    let zag=document.querySelector('meta[property="og:title"]').getAttribute('content');
    //let url=document.querySelector('meta[property="og:url"]').getAttribute('content');
   // console.log(urlold +' '+zag+' '+path+ ' '+ location.hostname);
  //  if(location.hostname=='japson.ru' && parseInt(nofoto) ){
		if(location.hostname=='japson.ru'){
        ym(50011537, 'hit', fullUrl, {title: zag, referer: urlold});
	 ga('send', {   hitType: 'pageview',   page: location.pathname });

        console.log(fullUrl);
    }


}
function goArticle(prev,next,list){
    $('.pen_list.nasad').attr('name',prev[0]).attr('dathref',prev[1]).attr('redir',prev[2]);
    $('.pen_list.vpered').attr('name',next[0]).attr('dathref',next[1]).attr('redir',next[2]);
    $('.pen_list.list').attr('name',list[0]).attr('dathref',list[1]);
}


window.addEventListener('popstate', function(e){
     //console.log(e.target.location);
   //  console.log(e.state);
    remakeMenu(e.state,e.target.location);
    var path=e.target.location.pathname.substr(1);
    // console.log(':: '+decodeURIComponent(path));
    return false;
    // var newput=ajaxRedirect(path,0);
}, false);

function remakeMenu(state){
    //var oldmenu=$('#sdt_menu').attr('name');
    var oldmenu=curState.get('menu');
    var oldmid=curState.get('middle');
   // var oldmid=$('#mainpages').attr('name');
    if(arguments.length>1)
    var newhref=(arguments[1].toString());
    coffUrl(state.cofmen,state.cofrasd);
    if(oldmenu!=state.menu) {
       // console.log(state);
        var mass=state.menu.split('_');
        var newmas=[mass[0],'',mass[1],mass[2],'hmr'];
        makerMenu(newmas,'middle_menu',state.menu,middlmen,newhref);
        curState.set('menu',state.menu);
        }
    if(oldmid!=state.middle) {
        mass=state.middle.split('_');
        if(mass[1]>0) {var swtch='ra';} else{swtch='ma';}
        if(mass[2]>0) {var swtch='oa';}
        newmas=[mass[0],'',mass[1],mass[2],swtch];
      // console.log('remakeMenu '+state.middle);
        //if(state.yakor){ myYakor.set(state.yakor);}
        makerMenu(newmas,'mainpages',state.middle,noner,newhref,state.yakor);
        curState.set('middle',state.middle);
        loadComment(1);
    } else{
    if(state.yakor) {goActPage.init();goActPage.goPage(1,state.yakor.replace('#','')); }
    }
    //console.log('%% '+curState.getall());
}
function myYakor(yak){
     goActPage.init();
       goActPage.goPage(1,yak.replace('#',''));


}

function initcurState(){
    var men=$('#sdt_menu').attr('name') || '0_0_0';
    var mid=$('.secstr ').eq(0).attr('name') || '0_0_0';
    $('#mainpages').attr('name',mid);
    curState.set('middle',mid);
    curState.set('menu',men);
    curState.set('cofmen',$('#cofemenUP').text());
    curState.set('cofrasd',$('#cofemenu').text());
    initmakeUrl();
 //   console.log(curState.getall());
}

function initmakeUrl(){
    var path=window.location;
    var pathArray=path.toString().split('/');
    var elem_last=pathArray[pathArray.length-1];
    if(elem_last.charAt(0)=='#') {goActPage.init();goActPage.goPage(1,elem_last.replace('#',''));
        curState.set('yakor',elem_last); }
}



// соц коннект --------
function callUlogin____(token){
    $.getJSON("//ulogin.ru/token.php?host=" + encodeURIComponent(location.toString()) + "&token=" + token + "&callback=?", function(data){
        data = $.parseJSON(data.toString());
        if(!data.error){
            //alert("Привет, "+data.first_name+" "+data.last_name+"!");
            console.log(data);
            let nm=new Social();
            nm.rega(data,1,"/mod/social.php");
           // console.log(out);
          //  if(out.length) $('.socialstatus').html(out);
        }
    });
}

function Social____________(){
    this.ajax= function(ulogin,put) {
        $.ajax({
            type: "POST", url: put, data: "ulet="+JSON.stringify(ulogin),
            success: function (dat) {
                 console.log( "Прибыли данные2: " + dat  ); //+ data
                 var data = JSON.parse(dat);
                $('.socialstatus').html(data);
            }
        });
    };
     this.rega=function(ulogin, param,put) {
        ulogin.param=param;
      //   console.log(ulogin);
        this.ajax(ulogin,put);
    }

}

function ExitSocial(){
    $.ajax({
        type: "POST", url: "/mod/exitsocial.php",
        success: function (dat) {
            // console.log( "Прибыли данные: " + dat  ); //+ data
           // var data = JSON.parse(dat);
            $('.socialstatus').html(dat);
            let url = window.location.href;
            url = url.split('?')[0];
            history.replaceState(curState.getall(), 'namepage', url);
           // uLogin.customInit('uLogin_22addfea');
        }
    });
}
function delCode(){
    let url = window.location.href;
    url = url.split('?')[0];
    history.replaceState(curState.getall(), 'namepage', url);
}
function changeNick(){
let name=$('.nickname').children('span');
if ($(name).children('input').val()) {
    $.ajax({
    type: "POST", url:"/mod/changer.php", data: {id: 1, typ: $(name).children('input').val()},
        success:    function (dat) {
        //    console.log("Прибыли данные: " + dat); //+ data
            let data = JSON.parse(dat);
            if(data[0]){$(name).html(data[1]);}
    } });
}
else{if($(name).children('input').length) {$(name).html($(name).attr('data'));}else {$(name).attr('data',$(name).html());$(name).html('<input type="text">');}}
}

$(document).on("click", "#logid", function(event){ // нет кнопки
    var nm=new Social();
    nm.rega("56546",1,"/mod/social.php");
});

// управление плеером --------------------------------------------------

$(document).on("click", ".songplay", function(event){
    let elem=event.target||event.srcElement;
    let id=$(elem).parent().attr('id');
    let newSong=manageSongs;
    newSong.init();
    newSong.addSong(id,'one');
});
$(document).on("click", ".songadd", function(event){
    let elem=event.target||event.srcElement;
    let id=$(elem).parent().attr('id');
    let newSong=manageSongs;
    newSong.init();
    newSong.addSide(id,'one');
});
$(document).on("click", ".selectplaylistchild", function(event){
    let elem=event.target||event.srcElement;
    let id=$(elem).attr('id');
    let newSong=manageSongs;
    let par=$(elem).parent();
   // $(par).prepend("<div class='waiting'> Дождитесь загрузки кассеты... </div>");
    newSong.init();
    newSong.addList(id,'all');
});


 let manageSongs={

    init: function () { this.song={};   },

    getSong: function(id,param){
       return new Promise(function(succeed, fail) {
            $.ajax({
                type: "POST", url: "/mod/selsong.php", data: {id: id, typ: param},
                success: function (dat) {
                  //  console.log("Прибыли данные: " + dat); //+ data
                    let data = JSON.parse(dat);
                    succeed(data);
                }
            });//ajax
        });
        },
     saveList:function(){
         let el = $('#vc-container');
         let storona = el.data().cassette.options;
         let curobj={sides:storona.sides,nomsongs:storona.nomsongs};
         $.ajax({
             type: "POST", url: "/mod/makesong.php", data: curobj,
             success: function (dat) {
                 //  console.log("Прибыли данные: " + dat); //+ data
               //  var data = JSON.parse(dat);
              //   succeed(data);
             }
         });//ajax
     },
     addSong: function(id,param){
         let _self=this;
         this.getSong(id,param).then( function(massiv) {
             let el = $('#vc-container');
             let storona = el.data().cassette.currentSide; //номер стороны
             let tek_vrem = el.data().cassette._getSide().current; // id: "side2", status: "middle", playlist: Array[2], duration: 433.781932, playlistCount: 2 } playlistCount список песен
             el_new = $('#vc-container').data().cassette;
           //  el_new._setButtonActive( $( this ) );
             el_new._stop(); //
            // if(el_new.howlerSong.playing()){el_new.howlerSong.stop();}
            // el_new._clear(); //
              storona=el.data().cassette.currentSide;
             el.data().cassette._switchSidesA(storona);
             cursong=massiv['put'].replace('&amp;','&');
             cursong=cursong.replace('%20',' ');
             el_new.options.songNames=[massiv['song']];
             el_new.options.songs=[cursong];
             el_new.options.nomsongs=[massiv['id']];
             el_new.options.sides=['side1'];
             el_new.options.times=[massiv['times']];
           //  console.log(el_new.options.songs);
             $.when( el_new._createOneSidesAll( el_new.options.sides) ).done( function() {
                 el_new._vyvodSide('.spisokside',1);
                 el_new.cntTime=0;
                 el_new._progrLoad(0); // при смене листа обязательно обновить прогресс бар
                 marqueRun();
                setTimeout(function(){el_new._setButtonActive($(this));el_new._progrLoad(1);
                    el_new.cntTime=0;
                  // var perem="https://japson.ru/mitya/mitya_music/holya-nogtey/2003 - holyanogteq/side A/08 - Moj Chikago.mp3";
                  //  console.log(el_new);
                 //   var sound = new Howl({  src: ['/catalog/punkts/'+cursong],html5: true  });
                 //   console.log(sound);
                  //  sound.once('load', function(){
                 //       sound.play();
                  //  });
                    el_new._play();_self.saveList();
                    },500);

             });

        });
     },
     addSide: function(id,param){
         let _self=this;
         this.getSong(id,param).then( function(massiv) {
             let el = $('#vc-container'); let songs=[]; let songNames=[]; let sides=[]; let times=[];  let storona2;
             let nomsongs=[];
             var storona = el.data().cassette.currentSide; //номер стороны
             if(storona==1){storona2="side2";storona="side1";}else{storona2="side1";storona="side2";}
             var tek_side = el.data().cassette._getSide().current; // id: "side2", status: "middle", playlist: Array[2], duration: 433.781932, playlistCount: 2 } playlistCount список песен
            // console.log(tek_side);
             for(var i=0;i<tek_side.playlistCount;i++){
                 songs.push(tek_side.playlist[i].name);
                 songNames.push(tek_side.playlist[i].nameSong);
                 nomsongs.push(tek_side.playlist[i].nomsong);
                 times.push(tek_side.playlist[i].duration);
                 //sides[tek_side.playlist[i].name]=storona;
                 sides.push(storona);
             }
             //if(storona==1){storona2=2;}else{storona2=1;}
             tek_side = el.data().cassette._getSide().reverse;
             for(var i=0;i<tek_side.playlistCount;i++){
                 songs.push(tek_side.playlist[i].name);
                 songNames.push(tek_side.playlist[i].nameSong);
                 nomsongs.push(tek_side.playlist[i].nomsong);
                 times.push(tek_side.playlist[i].duration);
                // sides[tek_side.playlist[i].name]=storona2;
                 sides.push(storona2);
             }
            // console.log(songs);
             cursong=massiv['put'].replace('&amp;','&');
             songs.push(cursong);
             songNames.push(massiv['song']);
             sides.push(storona);
             nomsongs.push(massiv['id']);
             times.push(massiv['times']);
              //console.log(songs);
             el_new = $('#vc-container').data().cassette;
             el_new._stop();
                 el_new.options.songNames=songNames;
                 el_new.options.songs=songs;
             el_new.options.nomsongs=nomsongs;
             el_new.options.sides=sides;  el_new.options.times=times;
            // console.log(times);
             $.when( el_new._createOneSidesAll(sides) ).done( function() {
                 el_new._progrLoad(0); // при смене листа обязательно обновить прогресс бар
                 marqueRun();
                 _self.saveList();
               //  setTimeout(function(){el_new._setButtonActive($(this));el_new._play();el_new._progrLoad(1);},500);
             });

            // console.log(songs);
         });
     },
     addList: function(id,param){
         let _self=this;
         this.getSong(id,param).then( function(massiv) {
             let el = $('#vc-container'); let sides=[];
             let storona = el.data().cassette.currentSide; //номер стороны
             var tek_vrem = el.data().cassette._getSide().current;
             el_new = $('#vc-container').data().cassette;
             el_new._stop(); //
             el_new.cntTime=0; el_new._progrLoad(0);
            // console.log(massiv);
             storona=el.data().cassette.currentSide;
             el.data().cassette._switchSidesA(storona);
             el_new.options.songNames=[]; el_new.options.songs=[];el_new.options.nomsongs=[];
             el_new.options.sides=[]; el_new.options.times=[];
             massiv.forEach(function(value) {
                 cursong = value['put'].replace('&amp;', '&');
                // cursong = cursong.replace('%20', ' ');
                 el_new.options.songNames.push(value['song']);
                 el_new.options.songs.push (cursong);
                 el_new.options.nomsongs.push(value['id']);
                 el_new.options.times.push(value['times']);
                 if(value['side']==2){ el_new.options.sides.push('side2');}
                 else{ el_new.options.sides.push('side1');}
             });
            // console.log('_createOneSides');
            // $.when( el_new._createOneSides(el_new.options.sides) ).done( function() {
                 $.when( el_new._createOneSidesAll( el_new.options.sides) ).done( function() {
                     marqueRun();
                     el_new.cntTime=0;
                  //   el_new._progrLoad(0);
                 //console.log('_createOneSides after');
                     setTimeout(function(){ el_new._setButtonActive($(this));el_new._progrLoad(1);
                         el_new.cntTime=0;
                         el_new._play();
                     _self.saveList();},500);
                     //$('.waiting').remove();
                 });

             // console.log(el_new.options.songNames);
         });
     },

};

/*$(document).on("click", ".songplay", function(event){

});*/

// comments-------------------------
$(document).on("click", "#buttoncomm", function(event){
  //  console.log(curState.getall());
    let newcomm=commFunc;
    newcomm.inputComm($('#textarea1').val());
    newcomm.sendComm('new0');
});
$(document).on("click", ".commaddsend", function(event){
    let elem=event.target||event.srcElement;
    let id=$(elem).parent('.commaddopen').attr('id');
    let txt=$(elem).parent('.commaddopen').children('textarea').val();
    let newcomm=commFunc;
    newcomm.inputComm(txt);
    newcomm.sendComm(id);
});

let commFunc={
        comm:'',
    sendComm: function(elem){
        //this.recieveComm();
        let rasdel=curState.getall();
     //   console.log(comm+elem+rasdel.middle);
        $.ajax({
            type: "POST", url: "/mod/comment.php", data: {updatcom: elem, typ: comm, part:rasdel.middle},
            success: function (dat) {
                 // console.log("Прибыли данные: " + dat); //+ data
                var data = JSON.parse(dat);
                if(data[0]) {
                    let out = '<div class="comlist">' + data[1] + '</div>';
                    $('.listcomment').html(out);
                    $('.comlist').niceScroll({cursorcolor: "#77262a", cursorwidth: '7'});
                    if (elem == 'new0') {
                        $('#textarea1').val('');
                    }
                }
            }
        });//ajax
    },
    recieveComm:function(){
       // comm=$('#textarea1').val();
        //console.log(comm);
    },
    inputComm:function(val){        comm=val;    }
   // situation:function (param) {updatcom= param;  }
};
$(document).on("click", ".cass_name", function(event){
    let elem=event.target||event.srcElement;
    let kod=$(elem).parent('.cass_list').attr('id');
    $.ajax({
        type: "POST", url:"/mod/changer.php", data: {id: 2, jens: kod},
        success:    function (dat) {
         //       console.log("Прибыли данные: " + dat); //+ data
            $("body").append('<div id="overlay">fgdfgdf</div>');   //<!-- Пoдлoжкa -->
            let main=$(".gridbody");
            $(main).append("<div class='up_list'> Тест </div>");
            let data = JSON.parse(dat);
         //   if(data[0]){$(name).html(data[1]);}
           $('.up_list').html(data);
        } });
});
// удаление оверлея
function delOverley() { $('.up_list').remove();$('#overlay').fadeOut(400);$('#overlay').remove();	}
//--------------------------------------------удаление оверлея----------------------
$(document).on("click", "#overlay", function(event){delOverley();});

$(document).on("click", ".switchlist", function(event){
    let elem=event.target||event.srcElement;
    let kod=$(elem).attr('id');
    makeCasset(kod);
});