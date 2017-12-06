/**
 * Created by RARETA on 05.12.2017.
 */
function goUrl(event){
    var elem=event.target||event.srcElement;
    var men=$(elem).closest('div').parent('div').attr('name');
    var mass=men.split('_');
    var newmas=[mass[0],'',mass[1],mass[2],'hm'];
    //var path=e.target.location.pathname.substr(1);
    // console.log('::'+decodeURIComponent(path));
   // path=decodeURIComponent(path);
    makerMenu(newmas);
    console.log(window.location.toString());

}

var makerMenu=function(mass){
    var data3={key:mass[0],tbl:mass[1],km:mass[2],kr:mass[3],what:mass[4]};
    $.ajax({
        type: "POST",   url: "mod/hormenu.php",   data: data3,
        success: function(data){
             console.log( "Прибыли данные: " + data  ); //+ data
          //  data= JSON.parse(data);
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