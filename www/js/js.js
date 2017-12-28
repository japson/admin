/**
 * Created by RARETA on 05.12.2017.
 */
function currentState(){
    var state={menu:'',middle:'',right:'',left:''};
    var set=function(name,val){state[name]=val; }
    var get=function(name){return state[name];}
    var getall=function(){return state;}
    return{set:set, get:get,getall:getall};
};
var curState=currentState();

function goUrl(event){ // главное
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
    curState.set('menu',men);
    curState.set('middle',men);
   // console.log(curState.getall());
    history.pushState(curState.getall(), 'namepage', newhref);
    console.log(window.location.toString());

}

var makerMenu=function(mass,id,men,callback){
    var data3={key:mass[0],tbl:mass[1],km:mass[2],kr:mass[3],what:mass[4]};
    $.ajax({
        type: "POST",   url: "mod/hormenu.php",   data: data3,
        success: function(data){
           //  console.log( "Прибыли данные: " + data  ); //+ data
            data= JSON.parse(data);
            $('#'+id).html(data[0]);
          //  console.log(data[1][0]);
            if(mass[4]=='hm') $('#mainpages').html(data[1][0]).attr('name',men);
            callback();
        }// success
    });
    console.log(curState.getall());
}

window.addEventListener('popstate', function(e){
    // console.log(e.target.location.pathname);
    console.log(e.state);
    remakeMenu(e.state);
    var path=e.target.location.pathname.substr(1);
     console.log(':: '+decodeURIComponent(path));
    return false;


    path=decodeURIComponent(path);
    if(path.length==0){path='index';}
    if(path=='index.php' || path=='index.htm' || path=='index.html'){path='index';}
    //path=changeRus(path);
    var temp=menuPunkt.history(path);
    //console.log(temp);
    detectHref(temp, 0,0);
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
        newmas=[mass[0],'',mass[1],mass[2],'ma'];
        console.log('remakeMenu '+state.middle);
        makerMenu(newmas,'mainpages',state.middle,noner);
        curState.set('middle',state.middle);
    }

function noner(){return false;}



}