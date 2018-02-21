/**
 * Created by RARETA on 05.12.2017.
 */
function currentState(){
    var state={menu:'',middle:'',yakor:'',right:'',left:'',cofmen:'',cofrasd:''};
    var set=function(name,val){state[name]=val; };
    var get=function(name){return state[name];};
    var getall=function(){return state;};
    return {set:set, get:get,getall:getall};
}


function noner(){return false;}
var curState=currentState();
middlmen();
var goActPage=goPageObj();
initcurState();
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
   function goRasd(event) {var elem=event.target||event.srcElement;
       event.preventDefault();
    var men=$(elem).closest('a').attr('name');
    console.log(men);
    var mass=men.split('_');
    var newmas=[mass[0],'',mass[1],mass[2],'ra'];
    var newhref=$(elem).closest('a').attr('href');
      // history.replaceState(curState.getall(), 'namepage2', '');
    //   console.log(newhref);
   // curState.set('menu',men);
    curState.set('middle',men);
       curState.set('yakor','#1');
       makerMenu(newmas,'mainpages',men,noner,newhref);
       var urlrasd=$(elem).closest('a').find('.sdt_link').text();
       coffUrl('',urlrasd);
       curState.set('cofrasd',urlrasd);
    //   console.log(window.history.length);
    //   console.log(window.history.state);
       var peremsost=curState.getall();
      // history.replaceState(curState.getall(), 'namepage2', '');
      // history.replaceState(peremsost, null, window.location);
       history.pushState(peremsost, null, newhref);

//console.log($(elem).closest('a').attr('name'));
//return false;
}
$(document).on("click", "a.linkarticle", function(event){
    var elem=event.target||event.srcElement;
    var men=$(elem).closest('tr').attr('name');
    var mass=men.split('_');
    var newmas=[mass[0],'',mass[1],mass[2],'oa'];
    var newhref=$(elem).closest('a').attr('href');
    console.log(newhref);
    curState.set('middle',men);
    curState.set('yakor','#1');
    makerMenu(newmas,'mainpages',men,noner,newhref);
    var peremsost=curState.getall();
    history.pushState(peremsost, null, newhref);
    return false;
});


function makerMenu(mass,id,men,callback,newhref){
    var data3={key:mass[0],tbl:mass[1],km:mass[2],kr:mass[3],what:mass[4],href:newhref};
    console.log (data3);
    $.ajax({
        type: "POST",   url: "/mod/hormenu.php",   data: data3,
        success: function(dat){
         // console.log( "Прибыли данные: " + dat  ); //+ data
            var data= JSON.parse(dat);

           // console.log(data[1][0]);
            if(mass[4]=='hm' || mass[4]=='hmr') $('#'+id).html(data[0]);
            if(mass[4]=='hm') $('#mainpages').html(data[1][0]).attr('name',men);
            if(mass[4]=='ma') $('#mainpages').html(data[1][0]).attr('name',men);
            if(mass[4]=='ra') $('#mainpages').html(data[1][0]).attr('name',men);
            if(mass[4]=='oa') $('#mainpages').html(data[1][0]).attr('name',men);
           // console.log(mass[4]); console.log(data[1][1]);
           if(mass[4]!='hmr') meta(data[1][1]);
            callback();
            //history.pushState(curState.getall(), 'namepage', newhref);
        }// success
        /*complete: function(jqXHR, textStatus){
            console.log(textStatus);
            console.log(jqXHR);
        }*/
    });
    var meta=function(data){
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

window.addEventListener('popstate', function(e){
    // console.log(e.target.location.pathname);
  //  console.log(e.target.location.pathname.substr(1));
    remakeMenu(e.state,e.target.location);
    var path=e.target.location.pathname.substr(1);
     console.log(':: '+decodeURIComponent(path));
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
        console.log('remakeMenu '+state.middle);
        makerMenu(newmas,'mainpages',state.middle,noner,newhref);
        curState.set('middle',state.middle);
    }
    if(state.yakor) {goActPage.init();goActPage.goPage(1,state.yakor.replace('#','')); }
    console.log(curState.getall());
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

// управление плеером --------------------------------------------------

$(document).on("click", ".songplay", function(event){
    var elem=event.target||event.srcElement;
    var id=$(elem).parent().attr('id');
    var newSong=manageSongs;
    newSong.init();
    newSong.addSong(id,'one');
});
$(document).on("click", ".songadd", function(event){
    var elem=event.target||event.srcElement;
    var id=$(elem).parent().attr('id');
    var newSong=manageSongs;
    newSong.init();
    newSong.addSide(id,'one');
});

 var manageSongs={

    init: function () { this.song={};   },

    getSong: function(id,param){
       return new Promise(function(succeed, fail) {
            $.ajax({
                type: "POST", url: "/mod/selsong.php", data: {id: id, typ: param},
                success: function (dat) {
                  //  console.log("Прибыли данные: " + dat); //+ data
                    var data = JSON.parse(dat);
                    succeed(data);
                }
            });//ajax
        });
        },
     addSong: function(id,param){
         this.getSong(id,param).then( function(massiv) {
            var el = $('#vc-container');
            var storona = el.data().cassette.currentSide; //номер стороны
            var tek_vrem = el.data().cassette._getSide().current; // id: "side2", status: "middle", playlist: Array[2], duration: 433.781932, playlistCount: 2 } playlistCount список песен
             el_new = $('#vc-container').data().cassette;
           //  el_new._setButtonActive( $( this ) );
             el_new._stop(); //

            // el_new._clear(); //
             var storona=el.data().cassette.currentSide;
             el.data().cassette._switchSidesA(storona);
             cursong=massiv['put'].replace('&amp;','&');
             el_new.options.songNames=[massiv['song']];
             el_new.options.songs=[cursong];

             $.when( el_new._createSides() ).done( function() {
                 el_new._vyvodSide('.spisokside',1);
                 el_new.cntTime=0;
                 el_new._progrLoad(0); // при смене листа обязательно обновить прогресс бар
                 marqueRun();
                setTimeout(function(){el_new._setButtonActive($(this));el_new._play();el_new._progrLoad(1);},500);

             });

            // console.log(el_new.options.songNames);

            // cursong= cursong.replace('%20',' ');

                         //el_new._loadLoud();
                          //myOb.sound = new $.Sound();

            // console.log(el.data().cassette._getSide().current);
            //   console.log(el_new.options.songs);

        });
     },
     addSide: function(id,param){
         this.getSong(id,param).then( function(massiv) {
             var el = $('#vc-container'); var songs=[]; var songNames=[]; var sides=[];  var storona2;
             var storona = el.data().cassette.currentSide; //номер стороны
             if(storona==1){storona2="side2";storona="side1";}else{storona2="side1";storona="side2";}
             var tek_side = el.data().cassette._getSide().current; // id: "side2", status: "middle", playlist: Array[2], duration: 433.781932, playlistCount: 2 } playlistCount список песен
           //  console.log(tek_side);
             for(var i=0;i<tek_side.playlistCount;i++){
                 songs.push(tek_side.playlist[i].name);
                 songNames.push(tek_side.playlist[i].nameSong);
                 //sides[tek_side.playlist[i].name]=storona;
                 sides.push(storona);
             }
             //if(storona==1){storona2=2;}else{storona2=1;}
             tek_side = el.data().cassette._getSide().reverse;
             for(var i=0;i<tek_side.playlistCount;i++){
                 songs.push(tek_side.playlist[i].name);
                 songNames.push(tek_side.playlist[i].nameSong);
                // sides[tek_side.playlist[i].name]=storona2;
                 sides.push(storona2);
             }
            // console.log(songs);
             cursong=massiv['put'].replace('&amp;','&');
             songs.push(cursong);
             songNames.push(massiv['song']);
             sides.push(storona);
             //console.log(sides); console.log(songs);
             el_new = $('#vc-container').data().cassette;
             el_new._stop();
                 el_new.options.songNames=songNames;
                 el_new.options.songs=songs;
             $.when( el_new._createOneSides(sides) ).done( function() {
                 el_new._progrLoad(0); // при смене листа обязательно обновить прогресс бар
                 marqueRun();
               //  setTimeout(function(){el_new._setButtonActive($(this));el_new._play();el_new._progrLoad(1);},500);
             });

            // console.log(songs);
         });
     }

};
