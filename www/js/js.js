/**
 * Created by RARETA on 05.12.2017.
 */
function currentState(){
    var state={menu:'',middle:'',right:'',left:''};
    var set=function(name,val){state[name]=val; };
    var get=function(name){return state[name];};
    var getall=function(){return state;};
    return {set:set, get:get,getall:getall};
}


function noner(){return false;}
var curState=currentState();
middlmen();
initcurState();
console.log(curState.getall());

function goUrl(event){ // главное
    event.preventDefault();
    var elem=event.target||event.srcElement;
    var men=$(elem).closest('div').parent('div').attr('name');
    var mass=men.split('_');
    var newmas=[mass[0],'',mass[1],mass[2],'hm'];
    //var path=e.target.location.pathname.substr(1);
    // console.log('::'+decodeURIComponent(path));
   // path=decodeURIComponent(path);
    makerMenu(newmas,'middle_menu',men,middlmen);
  //  middlmen();
    var newhref=$(elem).attr('href');
   // console.log(window.history.state);
    curState.set('menu',men);
    curState.set('middle',men);
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
       makerMenu(newmas,'mainpages',men,noner);
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
    makerMenu(newmas,'mainpages',men,noner);
    var peremsost=curState.getall();
    history.pushState(peremsost, null, newhref);
    return false;
});


function makerMenu(mass,id,men,callback){
    var data3={key:mass[0],tbl:mass[1],km:mass[2],kr:mass[3],what:mass[4]};
  //  console.log (data3);
    $.ajax({
        type: "POST",   url: "/mod/hormenu.php",   data: data3,
        success: function(dat){
         // console.log( "Прибыли данные: " + dat  ); //+ data
            var data= JSON.parse(dat);
            $('#'+id).html(data[0]);
           // console.log(data[0]);
            if(mass[4]=='hm') $('#mainpages').html(data[1][0]).attr('name',men);
            if(mass[4]=='ra') $('#mainpages').html(data[1][0]).attr('name',men);
            if(mass[4]=='oa') $('#mainpages').html(data[1][0]).attr('name',men);
            callback();
            //history.pushState(curState.getall(), 'namepage', newhref);
        }// success
        /*complete: function(jqXHR, textStatus){
            console.log(textStatus);
            console.log(jqXHR);
        }*/
    });
    console.log(curState.getall());
}

window.addEventListener('popstate', function(e){
    // console.log(e.target.location.pathname);
   // console.log(e);
    remakeMenu(e.state);
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
    if(oldmenu!=state.menu) {
        var mass=state.menu.split('_');
        var newmas=[mass[0],'',mass[1],mass[2],'hmr'];
        makerMenu(newmas,'middle_menu',state.menu,middlmen);
        curState.set('menu',state.menu);
        }
    if(oldmid!=state.middle) {
        mass=state.middle.split('_');
        if(mass[1]>0) {var swtch='ra';} else{swtch='ma';}
        if(mass[2]>0) {var swtch='oa';}
        newmas=[mass[0],'',mass[1],mass[2],swtch];
       // console.log('remakeMenu '+state.middle);
        makerMenu(newmas,'mainpages',state.middle,noner);
        curState.set('middle',state.middle);
    }
    console.log(curState.getall());
}
function initcurState(){
    var men=$('#sdt_menu').attr('name') || '0_0_0';
    var mid=$('.secstr ').eq(0).attr('name') || '0_0_0';
    $('#mainpages').attr('name',mid);
    curState.set('middle',mid);
    curState.set('menu',men);
}