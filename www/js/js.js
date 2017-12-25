/**
 * Created by RARETA on 05.12.2017.
 */
function currentState(){
    var state={menu:'',middle:'',right:'',left:''};
    var set=function(name,val){state[name]=val; }
    var get=function(name){return state[name];}
    var getall=function(name){return state;}
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
    makerMenu(newmas,'middle_menu',middlmen);
  //  middlmen();
    var newhref=$(elem).attr('href');
    curState.set('menu',men);
   // console.log(curState.getall());
    history.pushState(curState.getall(), 'namepage', newhref);
    console.log(window.location.toString());

}

var makerMenu=function(mass,id,callback){
    var data3={key:mass[0],tbl:mass[1],km:mass[2],kr:mass[3],what:mass[4]};
    $.ajax({
        type: "POST",   url: "mod/hormenu.php",   data: data3,
        success: function(data){
           //  console.log( "Прибыли данные: " + data  ); //+ data
            data= JSON.parse(data);
            $('#'+id).html(data[0]);
          //  console.log(data[1][0]);
            $('#mainpages').html(data[1][0]);
            callback();
            return false;

            // console.log(document.getElementsByClassName('Myconfirm')[0]);
            if(document.getElementsByClassName('Myconfirm')[0]!=undefined){$('.Myconfirm').html('<p></p>'+data[0]);}
            else{myConfirm(data[0], '');}
            document.body.massiv=data[1];
            console.log(data[1]);
            // console.log(data[2]);
        }// success
    });

}

window.addEventListener('popstate', function(e){
    // console.log(e.target.location.pathname);
    //console.log(e.state);
    remakeMenu(e.state.menu);
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

function remakeMenu(attr){
    var oldname=$('#sdt_menu').attr('name');
    if(oldname==attr) return false;
    var mass=attr.split('_');
    var newmas=[mass[0],'',mass[1],mass[2],'hm'];
    makerMenu(newmas,'middle_menu',middlmen);


}