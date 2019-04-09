// JavaScript Document
var newDirOption ; var newDirOption2 ;

function datPars(data){ //проверка ajax поступления22   
	var result=0;
	var answer='Problem on the server. Its not answer.';
	if (data.length>0) {
		// console.log( "Прибыли данные: " + JSON.parse(data) );
		var mass=JSON.parse(data);
		if (mass[0]['atribut']==0) {answer=mass[0]['text'];}
		else {result=1;answer=mass[0]['text'];}
		}
	return(Array(result,answer));	
	}

function login_a() // логин 
{
    var remember=1;//нужна ли кука
    $("#logname").parent("div").children(".errorlog").remove();
    $("#logpass").parent("div").children(".errorlog").remove();
    var lognam=$('#logname').val();
    var logpass=$('#logpass').val();
    var nampattern = new RegExp(/^[a-z0-9]+$/i);
    if(lognam.length==0 || !nampattern.test(lognam)){ err_out('#errorsave', 'Недопустимые символы в логине.' ); return(0);}
    if(!nampattern.test(logpass)){ err_out('#errorsave', 'Недопустимые символы в пароле.' ); return(0);}
    var data="lognam="+lognam+"&logpass="+logpass+"&remember="+remember;
    $.ajax({
        type: "POST",   url: "mod/conn/db_check.php",   data: data,
        success: function(data){
            console.log( "Прибыли данные: " + data );
            var mass=datPars(data);
            if (mass[0]==0) {err_out('#errorsave', mass[1]); $('#logname').val("");$('#logpass').val("");}
            else {location.reload();}
        }
    });
}
//--------------------------------error handle
function err_out(elem, text) { $(elem).empty(); $(elem).html("<div id='errorsave'>"+ text +"</div>");return;	}		


//-----------------------логаут
function logout_a() {
	$.ajax({
    type: "POST",   url: "mod/conn/logout.php",   data: "",
  success: function(data){
	// console.log( "Прибыли данные: " + data );
	 var mass=datPars(data);
     // location.reload();
	 if (mass[0]==1) { $(".container").html(mass[1]);}
  }
							   });	
	}	
	
//--------------------------------------------Окно подтверждения----------------------------
function myConfirm(perem, focelem){
	$("body").append('<div id="overlay"></div>');   //<!-- Пoдлoжкa -->
	var otmena="$('.Myconfirm').remove();$('#overlay').fadeOut(400);$('#overlay').remove();";
	
	$('#overlay').fadeIn(400, // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
		 	function(){ // пoсле выпoлнения предъидущей aнимaции
			$("body").append("<div class='Myconfirm'> <p>"+perem+"</p></div>");
			//$('.Myconfirm input').focus();
			if(focelem.length>0) {$('.Myconfirm input[id="'+focelem+'"]').focus();}	
			else{$('.Myconfirm input').eq(0).focus();}			
		});
	}

// удаление оверлея
function delOverley() { $('.Myconfirm').remove();$('#overlay').fadeOut(400);$('#overlay').remove();	}	
//--------------------------------------------удаление оверлея----------------------
$(document).on("click", "#overlay", function(event){delOverley();});	
	
// вызов меню создания пункта ---------------------------------
	function createMenu(){
    var param=document.getElementById('menu').massiv;
	var men=new createWindow('typmenu'); 	men.wincreate(param); }
function createUser(){
    var param=document.getElementById('users').massiv;
	var men=new createWindow('typuser'); 	men.wincreate(param);	}
function createParam(){
    var param=document.getElementById('settings').massiv;
	var men=new createWindow(''); 	men.wincreate(param);	}
function createSect(){
	var param=document.getElementById('section').massiv;
	var men=new createWindow('typmenu'); 	men.wincreate(param);
	}	
function createArticle(){
	var param=document.getElementById('post').massiv;
	//alert(JSON.stringify( param));
	var men=new createWindow(''); 	men.wincreate(param);	
	}
function createElem(){
	visualDir();
//	return false;
   // var param=document.getElementById('position').massiv;
   // var men=new createWindow(''); 	men.wincreate(param);
}
		

function createWindow(data){
	this.param	= data;
	this.wincreate = function(){
		$("#errorsave").html("");
		//console.log(arguments.length);
		if(arguments.length>0){var ukaz=arguments[0];}
			else if($('table').length==1) { ukaz={'table':$('table').attr('id')};}
			else {return false;}
		//if(arguments.length==3){arr=arguments[2]} else{ arr='';}
		$.ajax({

  		  type: "POST",   url: "mod/wincreate.php",   data: 'tbl='+JSON.stringify(ukaz)+'&param='+this.param ,
  			success: function(data){
	// console.log( "Прибыли данные: " + data );
			 var mass=datPars(data);
			 if (mass[0]==1) { myConfirm(mass[1], ''); }
	 		if (mass[0]==0) { $("#errorsave").html(mass[1]); }
  			}
		});	
	}
};

// запись данных ------------------------------
function saveNew(tabl){
	$("#errform").html('');
	//var inpt=$('.Myconfirm input');
	//var slct=$('.Myconfirm select');
	var massa='';
	var param=document.getElementById(tabl).massiv;
	if(typeof(param)=='object'){massa='&keys='+ JSON.stringify(param); }
var perem= new dataMyConfirm($('.Myconfirm'));
var itog=perem._init(); itog[1]=Array(itog[1]);
if(arguments[1]) itog[1]=arguments[1];
//console.log(itog);console.log(param);

    /*var data2 = new FormData();
    data2.append("keys", JSON.stringify(param));
    data2.append("param", 'tabl');
    data2.append("data", JSON.stringify(itog[1]));*/
	data3={keys:param,param:tabl,data:itog[1]};

//console.log(data2.getAll('data'));
//return false ;
if (itog[0].length>0){$("#errform").html('!!!'+itog[0]);}
	else{
  //  var jqxhr = $.post( "mod/winsave.php", data3,  function() {
   //     alert( "success" );    })
				$.ajax({
					type: "POST",   url: "mod/winsave.php",
				//data: 'data='+ encodeURI(JSON.stringify(itog[1]))+'&param='+tabl+massa,
				data: data3,
				//cache: false,
				//dataType: 'json',

					  success: function(data){
					// console.log( "Прибыли данные: " + data );
					// $("#errorsave").html(data);
					var mass=datPars(data);
					if (mass[0]==1) { location.reload(); }
					if (mass[0]==0) { $("#errform").html(mass[1]); }
					}
					});
		}
	}	
//----------------------------
dataMyConfirm	= function( element) {this.$el = $( element );}

dataMyConfirm.prototype={
	$el		:{}, 	out		:{}, 	err		:'',
	
	_init	: function(){
		var self = this;
		this.$el.find('input').each(function(indx, elem){var field=$(elem).attr('type'); var field_val=$(elem).val();
									tmp=self._choice(field,field_val);
									if(tmp.length==0){per=$(elem).attr('id'); self.out[per] =field_val ; }
									else{self.err=self.err+tmp;} });
		this.$el.find('select').each(function(indx, elem){var field=$(elem).find('option:selected').val(); 
									per=$(elem).attr('id');	self.out[per] =field ; });							
		//console.log(self.out);
		//console.log(self.err);
	return Array(self.err,self.out);								
	},
	_choice	: function(field,field_val){
		var tmp='';
		switch (field) {
 			 case 'text':
   			 //var nampattern = new RegExp(/^[a-z0-9-_<>]+$/i);
			 // if(field_val.length==0 ){ tmp='Недопустимые символы в логине.';} 
			// console.log(field);
   			 break;
		}
	return tmp;	
	}	
	
}	
// редактирование позиции-------- инпутироваине----------------------
function editRecord(event){
	$("#errorsave").html('');
	//var out={};
	var elem=event.target||event.srcElement; 
	var perem=new edRec(elem,'tr','td','name');
	var tbl=$(elem).closest('table').attr('id');
	perem._unroll();
	var data='tbl='+tbl+'&record='+perem.idnom+'&data='+JSON.stringify(perem.out);
   // console.log( "дата: " + data );
	$.ajax({
    	type: "POST",   url: "mod/recordedit.php",   data: data,
		  success: function(data){
		// console.log( "Прибыли данные: " + data );
		// $("#errorsave").html(data);
	 	var mass=datPars(data);
	 	if (mass[0]==1) {  $(elem).closest('tr').replaceWith(mass[1]);}
	 	if (mass[0]==0) { $("#errorsave").html(mass[1]); }
		}
		});
	
	
	}
	
edRec=function(element,tag,posit,atrbt){
	this.field=$(element).closest(tag);
	this.poisk=posit;
	this.idnom=$(element).closest(tag).attr('id');
	this.atribut=atrbt;
	//console.log(this.atribut);
	}	
edRec.prototype={
	field	: "",
	out		: {},
	poisk	: '',
	atribut	: '',
	_unroll	: function(){
		var self = this;
		self.out={};
		//console.log(this.field.find(this.poisk));
		this.field.find(this.poisk).each(function(indx,element){var attr = $(element).attr(self.atribut);
		if(attr !== undefined && attr !== false){self.out[indx]=$(element).attr(self.atribut);}});
		//console.log(self.out);
	},
	
	_recievedat	: function(){
		var self = this;
		self.out={};
		this.field.find(this.poisk).each(function(indx,element){ var attr=$(element).attr(self.atribut);
		
			switch (attr){
				case 'external':;
               // case 'redirect':;
			case 'vyvod': tmp=$(element).find('input').prop("checked"); if(tmp){self.out[attr]=1;}else{self.out[attr]=0;}  ;break;
			case 'rol': self.out[attr]=$(element).find('option:selected').val();  ;break;
            case 'type':
			case 'side': self.out[attr]=$(element).find('option:selected').val();  ;break;
			default: tmp=$(element).find('input'); if(tmp.length>0) {self.out[attr]=$(element).find('input').val();  } 
			}
		});
	//console.log(self.out);
	}
}

function saveRecord(event){ // - сохранение позиции ----------------------------------
	$("#errorsave").html('');
var elem=event.target||event.srcElement; 
	var perem=new edRec(elem,'tr','td','name');
	var tbl=$(elem).closest('table').attr('id');
	perem._recievedat();
	//var data='tbl='+tbl+'&record='+perem.idnom+'&data='+JSON.stringify(perem.out);
    var data={tbl:tbl,record:perem.idnom,data:perem.out};
	$.ajax({
    	type: "POST",   url: "mod/recordsave.php",   data: data,
		  success: function(data){
		// console.log( "Прибыли данные: " + data );
		 //$("#errorsave").html(data);
	 	var mass=datPars(data);
	 	if (mass[0]==1) {  $(elem).closest('tr').replaceWith(mass[1]);}
	 	if (mass[0]==0) { $("#errorsave").html(mass[1]); }
  		
		}
		});
}
// удаление позиции --------------------------------------------
transfMass=function(param){
    elem=document.body;
    elem.massiv=param;
    //console.log(param);
}

function delRecord(event) {
    $("#errorsave").html('');
    var elem = event.target || event.srcElement;
    var tbl = $(elem).closest('table').attr('id');
    //var nazvan=$(elem).closest('tr').children('td').eq(1).text();
    var nazvan = $(elem).closest('tr').children('td[name="name"]').text();
    if (nazvan.length == 0) nazvan = $(elem).closest('tr').children('td[name="note"]').text();
    massconfirm = '<div class="row delconfirm" id="delconfirm"><div class="confirm">' + 'Удалить позицию ' + nazvan + " ? </div><div class='row'><div class='col-sm-2 col-sm-offset-3'><button class='btn btn-success' onclick='delPosition(1);'>ОК</button></div><div class='col-sm-2 col-sm-offset-1'><button class='btn btn-warning' onclick='delOverley();'>Отмена</button></div></div></div>";

    var param = document.getElementById(tbl).massiv;
    param.nomrec = $(elem).closest('tr').attr('id');
    myConfirm(massconfirm, '', transfMass(param));

}
function delPosition(attr) {
    var data='data='+JSON.stringify(document.body.massiv);
    $.ajax({
        type: "POST",   url: "mod/recordel.php",   data: data,
        success: function(data){
          //  console.log( "Прибыли данные: " + data );
            //$("#errorsave").html(data);
            //	  return false;
            var mass=datPars(data);
            if (mass[0]==1) {location.reload(); }
            if (mass[0]==0) { $("#errorsave").html(mass[1]); }

        }
    });

}



// сортировка ----------------------------------
$(document).on("click", "i[name=sortarrowup]", function(event){ var elem=event.target||event.srcElement; sorty(elem,1);});
$(document).on("click", "i[name=sortarrowdown]", function(event){  var elem=event.target||event.srcElement; sorty(elem,0);});

function sorty (elem,direct){
	$("#errorsave").html('');
	var nomZam='';
	var field=$(elem).closest('tr').attr('id');
	var tbl=$(elem).closest('table').attr('id');
	var tram=$(elem).closest('tr');
	if(direct) {
		if($(tram).prev("tr").length) {nomZam=$(tram).prev("tr").attr('id');}
		}	else { if($(tram).next("tr").length) {nomZam=$(tram).next("tr").attr('id'); }		}
	if(nomZam.length==0) {return false;}
	
	var data='tbl='+tbl+'&record='+field+'&recordzam='+nomZam;
	$.ajax({
    	type: "POST",   url: "mod/sorty.php",   data: data,
		  success: function(data){
		// console.log( "Прибыли данные: " + data );
		 //$("#errorsave").html(data);
	 	var mass=datPars(data);
	 	if (mass[0]==0) { $("#errorsave").html(mass[1]); }
  		if (mass[0]==1) {  
			if(direct) {
			var cur_sort=$(tram).find("td[name='sort']").html();
			var zam_sort=$(tram).prev("tr").find("td[name='sort']").html();
			$(tram).find("td[name='sort']").html(zam_sort);
			$(tram).prev("tr").find("td[name='sort']").html(cur_sort);
			tram.insertBefore(tram.prev("tr"));
			} else {
			var cur_sort=$(tram).find("td[name='sort']").html();
			var zam_sort=$(tram).next("tr").find("td[name='sort']").html();
			$(tram).find("td[name='sort']").html(zam_sort);
			$(tram).next("tr").find("td[name='sort']").html(cur_sort);
			tram.insertAfter(tram.next("tr"));}
			}
		
		}
		});
	
}
// -- работа с redirect------------------------
$(document).on("click", ".tablredirect div", function(event){ var elem=event.target||event.srcElement;
    var tbl=$(elem).closest('table').attr('id');
    var param=document.getElementById(tbl).massiv;
    param.avaliable=$(elem).text();
    tbl=$(elem).closest('tr').attr('id'); // номер
   // var men=new createWinPict(tbl); 	men.wincreate(param);
  //  console.log(param);
    var data={param:param,id:tbl};
    $.ajax({
        type: "POST",   url: "mod/dirall.php",
        data: data,//"param="+JSON.stringify(param)+ '&put='+encodeURIComponent(JSON.stringify(put)),
        success: function(data){
            //  console.log( "Прибыли данные: " + data  ); //+ data

            data= JSON.parse(data);
            // console.log(data);

            myConfirm('<div id="mybar" >'+data['0_0']+'</div>'+data['outbutton'], '');
           // $('#mybar').html(data['0_0']);
            newDirOption=createMaSong(data);
           // document.getElementsByTagName('actionmenu').massiv=data;
            return false;
        }// success
    });

});

function redirSelect(event){    var elem=event.target||event.srcElement; addClass(elem);}


function redirSave () {
    var nam=$('tr.tblcolorstr').children('td').eq(0).children('div').attr('name');
    if(nam) {
        let param = newDirOption.punkt('record').split('_');
        nam=nam.split('_');
        let tmp=new Object(engineRedirect); tmp.init(param,{redirect : nam[0]},'Есть');
        tmp.ajax();
        // console.log(data);
    }
}

$(document).on("click", ".delredirect", function(event){ let elem=event.target||event.srcElement;
 let tmp=new Object(engineRedirect); tmp.init(['nom'+$(elem).attr('id'),'post'],{redirect : 0},'Нет');
    tmp.ajax();
});

var engineRedirect={
	datamass:{}, res:'',
    urljson: '',
	init:  (param,obj,result)=>{datamass={param:param,save:obj,result:result};
	res=result; urljson='mod/dirallsave.php' },
	ajax: () =>{$.ajax({type: "POST",   url: urljson, data: datamass,
        success: function(dat){ if(dat) {
            $('table#'+datamass.param[1]).find('tr#'+datamass.param[0]).children('.tablredirect').children('div').html(res);
            }   delOverley(); }// success
    		});
	}


}


// -- работа с картинками------------------------
$(document).on("click", ".tablpictur div", function(event){ var elem=event.target||event.srcElement; 
				var tbl=$(elem).closest('table').attr('id');
				var param=document.getElementById(tbl).massiv;
				tbl=$(elem).closest('tr').attr('id');
				var men=new createWinPict(tbl); 	men.wincreate(param);
				//console.log(param);
				});

// окно открытия картинок
function createWinPict(data){
	this.param	= data;
	this.wincreate = function(){
		$("#errorsave").html("");
		//console.log(arguments.length);
		if(arguments.length>0){var ukaz=arguments[0];}
			else { return false;}
		//if(arguments.length==3){arr=arguments[2]} else{ arr='';}
		//console.log(ukaz.table);
		$.ajax({
  		  type: "POST",   url: "mod/wincrtpct.php",   data: 'tbl='+JSON.stringify(ukaz)+'&krsd='+ this.param,
  			success: function(data){
	// console.log( "Прибыли данные: " + data );
			 var mass=datPars(data);
			 if (mass[0]==1) {myConfirm(mass[1], ''); 
	   		$(".photo.img-thumbnail").fancybox({     }); 
			 }
	 		if (mass[0]==0) { $("#errorsave").html(mass[1]); }
  			}
		});	
	}
};



//-------------------------------------
$(document).on("click", "#nosubmit", function(event){ 	

	event.stopPropagation(); // Остановка происходящего
    event.preventDefault();  // Полная остановка происходящего
 $("#errform").html('');
    // Создадим данные формы и добавим в них данные файлов из files
    var data = new FormData();
	//var znach=conv_symbol($('.Myconfirm input[id="namepict"]').val());
	//alert(znach);
	data.append("name", $("input[type=file]")[0].files[0]); //formData.append('userpic[]', myFileInput1.files[0], 'chris1.jpg');
	data.append("note", $('.Myconfirm input[id="namepict"]').val());
	data.append("file", $('.Myconfirm input[id="namefile"]').val());
	//data.append("nomer", $('.Myconfirm input[id="namepict"]').attr('name'));
	var nompos=$('.Myconfirm input[id="namepict"]').attr('name');
	data.append("nomer", nompos);
	var param=document.getElementById('namepict').massiv;
	//console.log(param);
	data.append("mass", JSON.stringify(param));

    $.ajax({
        url: 'mod/addfoto.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function(datAjax){
			// console.log( "Прибыли данные: " + (datAjax.atribut)  );
			// table_auto("section",datAjax);
			 
  if (datAjax[0].atribut==0) { $("#errform").html(datAjax[0].text); }
   if (datAjax[0].atribut==1) {
	   $('.Myconfirm ').html(datAjax[0].text); 
	   		//$('.Myconfirm .divtblpict').replaceWith(datAjax[0].text); 
   			//$('.Myconfirm #downformvvod').replaceWith(datAjax[0].text2);
   		 countpict(1,nompos);}
  		$(".photo.img-thumbnail").fancybox({    }); 
	    }//end success
    });
	});
	
//-------------------счетчик картинок
function countpict(operator, nompos) {
	//console.log(document.getElementById('namepict').massiv);
	var param=document.getElementById('namepict').massiv.table;
	//console.log(nompos);
var nomstr=nompos;//$('.divtblpict').find('tr').eq(0).attr('id');
	var priem=$('table[id="'+param+'"]').find('tr[id='+nomstr+']').find('[name="pictur"]').children('div').children('span');
	//console.log(priem);
	if(operator==1){	priem.text(priem.text()*1+1);}
	if(operator==0){	priem.text(priem.text()*1-1);}	
	}	
	
//- удалить картинку событие-----------------------------------
$(document).on("click", "#pict_del", function(event){
	var elem=event.target||event.srcElement; 
	var perem= new changePict(elem);
	perem.init();
	perem.formAction("<div class='col-sm-12'>Удалить картинку [ZAM]'?<p></p></div>",2,'');
	});		
//- изменить название картинки событие-----------------------------------
$(document).on("click", "#pict_pencil", function(event){
	var elem=event.target||event.srcElement; 
	var perem= new changePict(elem);
	perem.init();
	perem.formAction("<div class='col-sm-12'>Введите новую подпись под картинку: <input type='text' id='namepictxt' class='editpolesong' value='"+perem.tramv+"'></div>",1,'');
	});
//- изменить превью картинки событие-----------------------------------
$(document).on("click", "#pict_preview", function(event){
    var elem=event.target||event.srcElement;
    var perem= new changePict(elem);
    perem.init();
	var oldeditpict=$(elem).closest('table').find('.pict-up-thumbs').find('td#'+perem.id).children('a').attr('href');

    editpict=oldeditpict+'?'+Math.random();
	var coords='<input type="hidden" id="x" name="x" /> <input type="hidden" id="y" name="y" />        <input type="hidden" id="w" name="w" /> <input type="hidden" id="h" name="h" />';
	var povorot='<div class="povorot"><div> Поворот:<br><input type="radio" name="povorot" value="-1"> -90°<br><input type="radio" name="povorot" value="0" checked> 0°<br><input type="radio" name="povorot" value="1"> +90°</div><button id="rotatepict" class="btn btn-success" onclick="rotatePict();">OK</button></div>';
    perem.formAction("<div class='col-sm-12'>"+coords+"<div class='zagpreview'>Выбор новой областипревью и поворот картинки:</div><div class='makepreview'><img src='"+editpict+"' id='cropbox' > </div>"+povorot+"</div>",3,'');

    //rasmbox=parseInt(rasmbox,10);
    $('#cropbox').Jcrop({
        aspectRatio: 1,
        onSelect: updateCoords,
        setSelect:   [ 0, 0, 300, 300 ]
    });
   // if($('#cropbox').height()>$('#cropbox').width()){var rasmbox=$('#cropbox').width();}else{rasmbox=$('#cropbox').height();}
 //   console.log($('.jcrop-tracker').css('height'));
    //$('#cropbox').attr('src',oldeditpict);
});
function rotatePict() {
    var curr;
    $("[name='povorot']").each(function (index,elem) {
        if($(elem).prop('checked') )curr=($(elem).val());});
     if(curr!=0)   {
     	var tmp=document.getElementById('delpictconfirm').massiv;
     	tmp.param=4;
     	tmp.ugol=curr;
        // console.log (tmp);
         changePictName(1);
     }

}


$(document).on("change", "[name='povorot2222']", function(event){
    var curr;
    $("[name='povorot']").each(function (index,elem) {
		if($(elem).prop('checked') )curr=($(elem).val());
    });
	switch(curr){
		case '-1': $('.makepreview').addClass('leftrotate').removeClass('rightrotate');
		break;
        case '1': $('.makepreview').addClass('rightrotate').removeClass('leftrotate'); break;
        case '0': $('.makepreview').removeClass('rightrotate').removeClass('leftrotate'); break;
	}
	//console.log($("[name='povorot']").val());
});

function updateCoords(c)
{
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
};

function changePict(elem) { // функция изменения картинок ----------------------------------
	this.elem=elem;
	 this.massconfirm;
	 this.id; this.tramv; var idtbl; var idpos;
	this.init=function(){
		this.id=$(this.elem).parent('td').attr('id');
		idpos=$(this.elem).closest('tr').attr('id');
		idtbl=$(this.elem).closest('table').attr('id');
		this.tramv=$("tr.pict-up-thumbs").find('td[id='+this.id+']').children('a').attr('title');
	}
	
	this.formAction=function(envir,param,txt){
			 $("#errform").html('');
			this.massconfirm=envir.replace('[ZAM]',this.tramv);
			this.massconfirm='<div class="row delpictconfirm" id="delpictconfirm">'+this.massconfirm+"<div class='col-sm-2 col-sm-offset-4'><button class='btn btn-success' onclick='changePictName(1);'>ОК</button></div><div class='col-sm-2'><button class='btn btn-warning' onclick='changePictName(0);'>Отмена</button></div></div>";
			//console.log(document.getElementById('reservdata').massiv);
			if(document.getElementById('reservdata').massiv==undefined){
				table_auto("reservdata",{txt:$('#downformvvod').html()});
			}
			$('#downformvvod').html(this.massconfirm);
			table_auto("delpictconfirm",{id:this.id,param:param,text:txt,tbl:idtbl,kodpos:idpos});
			$('.buttpictform').css('display','none');
			//console.log ({id:this.id,param:param,text:txt,tbl:idtbl,kodpos:idpos});
	}
	
}

function changePictName(param){ // --- функция изменения картинок АЯКС ---------------------------
	var coords=[]; // добавляем координаты если есть блок превью
	if($('.makepreview').length) {
        var img = new Image();
        img.src = $('#cropbox').attr('src');
        var block={'width': $('.jcrop-holder').width(),'height': $('.jcrop-holder').height()};
        var kff=block.width / img.width;
        // console.log(kff);
		coords=[ $('#x').val(), $('#y').val(), $('#w').val(), $('#h').val(),kff];}
	var tmp=document.getElementById('reservdata').massiv;
	//console.log (tmp);
	if (param==0) {
		 $('#downformvvod').html(tmp.txt); delete document.getElementById('reservdata').massiv; $('.buttpictform').css('display','block');return false;}
	else{
			tmp=document.getElementById('delpictconfirm').massiv;
			var el=document.getElementById('namepictxt'); if(el) {tmp.text=$(el).val();}
        tmp.coords=coords;
		//console.log(JSON.stringify(tmp));
		$.ajax({
		type: "POST",   url: "mod/changephoto.php",   data: "data="+JSON.stringify(tmp),
 		 success: function(data){
		//	 console.log( "Прибыли данные: " + (data)  );
			 var mass=datPars(data);
			// console.log(tmp.kodpos);
			if (mass[0]==0) { $("#errform").html(mass[1]); }
  			 if (mass[0]==1) {
	 		  $('.Myconfirm ').html(mass[1]);
	 		  if(tmp.param==3) {
                  var kart = $('.pict-up-thumbs').children('td#' + tmp.id).find('img');
                  $(kart).attr('src', $(kart).attr('src') + '?' + Math.random());
              }
	   		//$('.Myconfirm .divtblpict').replaceWith(datAjax[0].text); 
   			//$('.Myconfirm #downformvvod').replaceWith(datAjax[0].text2);
   				if(tmp.param==2) {countpict(0,tmp.kodpos);}
			}
		 }
		  }); 
	}
	
}
// сортировка картинок----------------------------------
$(document).on("click", "#pict_left", function(event){ var elem=event.target||event.srcElement; sortyPict(elem,1);});
$(document).on("click", "#pict_right", function(event){  var elem=event.target||event.srcElement; sortyPict(elem,0);});

function sortyPict (elem,direct){
	$("#errform").html('');
	var nomZam='';
	var field=$(elem).closest('td').attr('id');
	var rasdel=$(elem).closest('tr').attr('id');
	var tbl=$(elem).closest('table').attr('id');
	var tram=$(elem).closest('td');
	if(direct) {
		if($(tram).prev("td").length) {nomZam=$(tram).prev("td").attr('id');}
		}	else { if($(tram).next("td").length) {nomZam=$(tram).next("td").attr('id'); }		}
	if(nomZam.length==0) {return false;}
	
	var data='tbl='+tbl+'&record='+field+'&recordzam='+nomZam+'&rasdel='+rasdel;
	$.ajax({
    	type: "POST",   url: "mod/sortypict.php",   data: data,
		  success: function(data){
		// console.log( "Прибыли данные: " + data );
		 //$("#errorsave").html(data);
	 	var mass=datPars(data);
	 	if (mass[0]==0) { $("#errform").html(mass[1]); }
  		if (mass[0]==1) {  
		
		$(elem).closest('table').find('td[id='+field+']').each(function(index,element){
			if(direct) {$(element).insertBefore($(element).prev("td")); 
			} else {$(element).insertAfter($(element).next("td"));}
			});
			
		}
	} //success
		});
	
}
//----------------------------------изменение новость	
function saveArticle(){
var obj= new changeNews('post');
obj.save();
	}
function viewArticle(){
var obj= new changeNews('post');
obj.view();
	}	
function editArticle(){
var obj= new changeNews('post');
obj.edit();
	}		

changeNews=function(place){  //   методы изменения новости
	$("#errorsave").html('');
	this.ob=place;
	this.param=document.getElementById(place).massiv;
	this.text2=CKEDITOR.instances.textID.getData();
	this.text_key={keys:$('#keywords').val(),decript:$('#description').val()};
	//this.text_key=$('#keywords').val();
	//this.text_descr=$('#description').val();
	//console.log(this.text_key);
	this.save = function(){
		 $.ajax({
    type: "POST",   url: "mod/newchange.php",   data: "param="+JSON.stringify(this.param)+"&textseo="+JSON.stringify(this.text_key)+"&text="+encodeURIComponent(JSON.stringify(this.text2))+"&tab=1",
  success: function(data){
	//  alert( "Прибыли данные: " + data  ); //+ data 
	 var mass=datPars(data);
	if (mass[0]==0) { $("#errorsave").html(mass[1]);}
	// location.reload(); 		 
  }// success
							   });	
		
	};
	this.view = function(){
	 $.ajax({
    type: "POST",   url: "mod/newchange.php",   data: "param="+JSON.stringify(this.param)+"&textseo="+JSON.stringify(this.text_key)+"&text="+encodeURIComponent(JSON.stringify(this.text2))+"&tab=2",
  success: function(data){
	//  alert( "Прибыли данные: " + data  ); //+ data 
	 var mass=datPars(data);
	if (mass[0]==0) { $("#errorsave").html(mass[1]);}
	if (mass[0]==1) {$('#cke_textID').remove(); $("#post").html(mass[1]);
	$('a[id="viewArticle"]').replaceWith('<a href="#" id="editArticle" onClick="editArticle(); return false;">Редактировать статью...</a>');
	}
	// location.reload(); 		 
  }// success
							   });	
	};
	this.edit = function(){
	 $.ajax({
    type: "POST",   url: "mod/newchange.php",   data: "param="+JSON.stringify(this.param)+"&textseo=''&text=''&tab=3",
  success: function(data){
	//  alert( "Прибыли данные: " + data  ); //+ data 
	 var mass=datPars(data);
	if (mass[0]==0) { $("#errorsave").html(mass[1]);}
	if (mass[0]==1) {$("#post").html(mass[1]);
	$('a[id="editArticle"]').replaceWith('<a href="#" id="viewArticle" onClick="viewArticle(); return false;">Посмотреть статью...</a>');
	editNew();
	}
	// location.reload(); 		 
  }// success
							   });	
	};
	
}
$(document).on("click", ".ckeditorselclass input", function(event) {
    var elem = event.target || event.srcElement;
    $('#cke_82_textInput').val($(elem).attr('id'));
        });

// перелистывание страниц в превью новости
var listing=0;
$(document).on("click", "#left_page", function(event){ var elem=event.target||event.srcElement; if(!listing){ changePage(elem,0);} });	
$(document).on("click", "#right_page", function(event){ var elem=event.target||event.srcElement; if(!listing){changePage(elem,1);} });	
	
	
function changePage(elem, direct){
	listing=1;
	var maspage=$('#primer').children('.stranic'); var curr;
	$(maspage).each(function(index,element){if(element.style.opacity==1){curr=$(element).attr('id'); } });
	if(direct){
		other=parseInt(curr.replace('page',''),10)+1;
		} else{other=parseInt(curr.replace('page',''),10)-1;
	}
	//console.log (other); console.log (curr); 
	if(other<1 || other>maspage.length){listing=0;return false;}
	else{curPage=$('#primer').children('#'+curr); otherPage=$('#primer').children('#page'+other); 
		otherPage.css('display','block').animate(   {    opacity : '1'  }, 700);
		curPage.animate(   {    opacity : '0'  }, 700);
		curPage.css('display','none');
		setTimeout(function() {listing=0;},700);
		
	}
	//	console.log (otherPage);
		
		//console.log (listing);
	//console.log(maspage.find('[style={opacity:1}]').attr('id'));
}	
	// визуализация директории выбора -------------------------------------------
	function visualDir() {
		var put={};
		if(arguments.length>0){put.put=arguments[0]; put.backput=arguments[1];} else{put.put='';}
        var param=document.getElementById('position').massiv;
        console.log("param="+JSON.stringify(param)+'&put='+JSON.stringify(put));
        $.ajax({
            type: "POST",   url: "/adm/mod/dirwatch.php",   data: "param="+JSON.stringify(param)+
			'&put='+encodeURIComponent(JSON.stringify(put)),
            success: function(data){
                //  console.log( "Прибыли данные: " + data  ); //+ data
                data= JSON.parse(data);
               // console.log(document.getElementsByClassName('Myconfirm')[0]);
                if(document.getElementsByClassName('Myconfirm')[0]!=undefined){$('.Myconfirm').html('<p></p>'+data[0]);}
                else{myConfirm(data[0], '');}
                document.body.massiv=data[1];
                //console.log(data[1]);
               // console.log(data[2]);
            }// success
        });
    }

	$(document).on('click', '.buttonselect',function(event) { var elem=event.target||event.srcElement; addClass(elem);	});
	
	function addClass(elem){
        var id=$(elem).closest('tr').attr('id');
		var tbl=$(elem).closest('table');
		$(tbl).find(".tblcolorstr").each(function(index,element){if($(element).attr('id')!=id){$(element).removeClass('tblcolorstr');
			$(element).find('.buttonselect').removeClass('select'); }});
        id=id.replace('direct','');
        $(elem).toggleClass('select');
        $(elem).closest('tr').toggleClass('tblcolorstr');
		//console.log(id);
	}

	$(document).on('click', '.tbldirsel',function(event) { var elem=event.target||event.srcElement;
        var data=document.body.massiv;
        var id=$(elem).closest('tr').attr('id');
        id=id.replace('direct','');
       // delOverley();
       // console.log('Iz obj: '+data['core'][2]+data['core'][1]+data[id][0]+'/');
        visualDir(data['core'][2]+data['core'][1]+data[id][0]+'/',data['core'][2]+data['core'][1]);

	});
	$(document).on('click', '#direturn',function(event) { var elem=event.target||event.srcElement;
        var data=document.body.massiv;
       // delOverley();
        var backput=(data['core'][2]+data['core'][1]).split('/');
        var newput='';
        for(var i=0;i<backput.length-3;i++) newput+=backput[i]+'/';
        //console.log(newput);
       // console.log(data['core'][3]);
        //console.log('Iz obj: '+data['core'][2]+data['core'][1]+data[id][0]+'/');
        visualDir(data['core'][3],newput);
    });
	function scanElement() {
        var data=document.body.massiv;
        var param=document.getElementById('position').massiv;
        var prefix=data['core'][1];
        var tbl=document.querySelector('table[id="tblposition"]');
        var elem=$(tbl).find(".tblcolorstr");
        var masselem=[];
        if(elem.length){
            elem.each(function(index,element){id=($(element).attr('id')).replace('direct','');masselem[index]=prefix+data[id][0];});
            $.ajax({
                type: "POST",   url: "mod/filewatch.php",   data: "param="+JSON.stringify(param)+
                '&put='+encodeURIComponent(JSON.stringify(masselem)),
                success: function(data){
                     console.log( "Прибыли данные: " + data  ); //+ data
                    var mass= JSON.parse(data);
                  /*  var out='';
                    for(var i=0;i<mass.length;i++){
                    	out+='<tr id="'+i+'"><td>'+mass[i]['artist']+'</td><td>'+mass[i]['title']+'</td><td>'+mass[i]['put']+'</td><td  class="button" name="delsong"><div>X</div></td></tr>';
					}
					out='<table class="previewtbl">'+out+'</table>';*/
                    console.log(mass);
					//if($('#tester') && $('#tester').html().length) {
					if(document.getElementById('tester')){
					//$('#tester').html(out);
					} else {$('.Myconfirm').append('<div id="tester">'+''+'</div>');}

                    document.getElementById('tester').massiv=mass;
                    createTester();
					//return false;


                    // console.log(document.getElementsByClassName('Myconfirm')[0]);
                 //   if(document.getElementsByClassName('Myconfirm')[0]!=undefined){$('.Myconfirm').html('<p></p>'+data[0]);}
                //    else{myConfirm(data[0], '');}
               //     document.body.massiv=data[1];
                //    console.log(data[1]);
                    // console.log(data[2]);
                }// success
            });
		}
        console.log(masselem);
    }
function emptyElement() {
		let tmp=[{"title":"empty","artist":"","length":0,"year":"0","track_number":"1","genre":"","album":"empty","bytrate":0,"time":"0:00","rasmer":0,"put":"empty","put-utf":"empty"}];
    if(document.getElementById('tester')){
    } else {$('.Myconfirm').append('<div id="tester">'+''+'</div>');}
    document.getElementById('tester').massiv=tmp;
    createTester();
    return false;
}

$(document).on('click', '.button[name="delsong"]',function(event){
    var elem=event.target||event.srcElement;
    var tr=$(elem).closest('tr').attr('id');
    var tmp=document.getElementById('tester').massiv;
    tmp.splice(tr, 1);
    document.getElementById('tester').massiv=tmp;
    createTester();
  //  console.log(tmp);
});
	function createTester(){ // создание списка выбранный пунктов
        var mass=document.getElementById('tester').massiv; var out='';
        for(var i=0;i<mass.length;i++){
            out+='<tr id="'+i+'"><td>'+mass[i]['track_number']+'</td><td>'+mass[i]['artist']+'</td><td>'+mass[i]['title']+'</td><td>'+mass[i]['put']+'</td><td  class="button" name="delsong"><div>X</div></td></tr>';
        }
        out='<table class="previewtbl">'+out+'</table>';
        $('#tester').html(out);
        document.getElementById('tester').massiv=mass;
	}

	function saveElements(tbl) {
        var tmp=document.getElementById('tester').massiv;
        var newmas=[];
      //  var newtmp={'title':tmp.title,'artist'}
		for(var i=0;i<tmp.length;i++) {var newtmp={'title':tmp[i].title,'artist':tmp[i].artist, 'length':tmp[i]['length'],'put':tmp[i].put}; newmas[i]=newtmp;}

        saveNew(tbl,newmas);
		//var tmp=document.getElementById('tester').massiv;
		//console.log(newmas);
	}

	function findSongs() { // поиск песен для ckeditor обновить директорию
		var data={format:'position'};
        $.ajax({
            type: "POST",   url: "mod/dirpunkt.php",
			 data: data,//"param="+JSON.stringify(param)+ '&put='+encodeURIComponent(JSON.stringify(put)),
            success: function(data){
               //  console.log( "Прибыли данные: " + data  ); //+ data
                data= JSON.parse(data);
               // console.log(data);
				$('#mybar').html(data['0_0']);
                newDirOption=createMaSong(data);
                document.getElementsByTagName('actionmenu').massiv=data;
			//	return false;

               // data= JSON.parse(data);
                // console.log(document.getElementsByClassName('Myconfirm')[0]);
              //  if(document.getElementsByClassName('Myconfirm')[0]!=undefined){$('.Myconfirm').html('<p></p>'+data[0]);}
             //   else{myConfirm(data[0], '');}
            //    document.body.massiv=data[1];
           //     console.log(data[1]);
                // console.log(data[2]);
            }// success
        });
    }
	$(document).on('click', '.dirpunkt',function(event) {
		var elem = event.target || event.srcElement;
		var tmp={};
		//tmp=document.getElementsByTagName('actionmenu').massiv;
        console.log(newDirOption);
		var newzn=$(elem).attr('id');
        var newtmp=newDirOption.punkt(newzn);
        $('#mybar').html(newtmp);
	});
	$(document).on('click', '.dirarticle',function(event) {
		var elem = event.target || event.srcElement;
		var tmp={};
		//tmp=document.getElementsByTagName('actionmenu').massiv;
		console.log(newDirOption);
		var newzn=$(elem).attr('id');
		var newtmp=newDirOption2.punkt(newzn);
		$('#mybar2').html(newtmp);
	});

	$(document).on('click', '.buttonselectsong',function(event) { // выбрать ссылку песни и вставить в поле текст ссылки
        var elem = event.target || event.srcElement; var makeurl='';
        var tr=$(elem).closest('tr');

        if($(tr).children('td').eq(1).text()=='file') {
            addAllSongs.init(tr);
            addAllSongs.select();
            addAllSongs.why();
		}
        if($(tr).children('td').eq(1).text()=='DIR') {
            addAllSongs.init(tr);
            addAllSongs.cassette();
            addAllSongs.why();
		}
    });

	let addAllSongs={
		elem:'',
		makeurl:'',
        init: function (giveme){elem=giveme;makeurl='';},

        select: function (){let kod='';
			$(elem).each(function(index,element) {
				if ($(element).children('td').eq(1).text() == 'file') {
					kod = $(element).children('td').eq(0).children('div').attr('id');
					title = $(element).children('td').eq(0).children('div').attr('title');
                    makeurl+='<div id="'+kod+'" class="selectplay" title="'+title+'"><span class="songplay"></span><span class="songtitle">'+title+'</span><span class="songadd"></span></div>';
            }
        })
		},
        cassette:function(){ let kod='';
            $(elem).each(function(index,element) {
                if ($(element).children('td').eq(1).text() == 'DIR') {
                    kod = $(element).children('td').eq(0).children('div').attr('id');
                    title = $(element).children('td').eq(0).children('div').text();
                    makeurl='<div class="selectplaylist"><div id="'+kod+'" class="selectplaylistchild" title="Включить кассету: &#013;'+title+'"></div></div>';
                }
            })
		},
        why: function (){$('.cke_dialog_contents').find('input[type=text]').val(makeurl);}

	};

	//--------------------ckeditor insert link
function findPages() { // поиск  обновить директорию
    var data={format:'post'};
    console.log(data);
    $.ajax({
        type: "POST",   url: "mod/dirpunkt.php",
        data: data,//"param="+JSON.stringify(param)+ '&put='+encodeURIComponent(JSON.stringify(put)),
        success: function(data){
             // console.log( "Прибыли данные: " + data  ); //+ data
            data= JSON.parse(data);
            // console.log(data);
            $('#mybar2').html(data['0_0']);
            newDirOption2=createMaSong(data);
            document.getElementsByTagName('actionmenu').massiv=data;

        }// success
    });
}
$(document).on('click', '.buttonselectartic',function(event) { // выбрать ссылку песни и вставить в поле текст ссылки
    var elem = event.target || event.srcElement; var makeurl='';
    var tr=$(elem).closest('tr');

    if($(tr).children('td').eq(1).text()=='article') {
       let ider= $(tr).children('td').eq(0).children('div').attr('id');
        data={format:'selart',id:ider};
        $.ajax({
            type: "POST",   url: "mod/rasnoe.php",
            data: data,//"param="+JSON.stringify(param)+ '&put='+encodeURIComponent(JSON.stringify(put)),
            success: function(data){
                 console.log( "Прибыли данные: " + data  ); //+ data
                data= JSON.parse(data);
                // console.log(data);
                $('.cke_dialog_contents').find('input[type=text]').val(data[1]);

            }// success
        });
    }

});




function createMaSong(mass) {
   // console.log(mass);
	var temp='';
    var links = {};// var i=0;
    links = mass;
    function punkt(elem) {
        var temp = '';
        for (var key in  links) {
        	if(key==elem) temp=links[key];
		}
      return temp;
    }
    return {punkt: punkt};
}

function secToTime(sec) {
    dt = new Date();
    dt.setTime(sec * 1000);
    return dt.getUTCHours() + ":" + dt.getUTCMinutes() + ":" + dt.getUTCSeconds();
}
function changePass(event){
    $("#errorsave").html('');
    var elem = event.target || event.srcElement;
    var tbl = $(elem).closest('table').attr('id');
    var nazvan = $(elem).closest('tr').children('td').eq(1).text();
    var tr = $(elem).closest('tr').attr('id');
    var temp="<div class='form-group passpole ' id='password'> <label class='control-label col-xs-4' for='namepassword'>Введите пароль: </label><div class='col-xs-7'><input type='password' class='form-control' id='password1' class='editpolesong' value=''></div></div>" ;
    temp+="<div class='form-group passpole ' id='password'> <label class='control-label col-xs-4' for='namepassword'>Повторите пароль: </label><div class='col-xs-7'><input type='password' class='form-control' id='password2' class='editpolesong' value=''></div></div>" ;
    var massconfirm = '<div class="row delconfirm" id="delconfirm"><div class="confirm">' + 'Изменить пароль пользователя: ' + nazvan + "  </div>"+temp+"<div class='row'><div class='col-sm-2 col-sm-offset-3'><button class='btn btn-success' onclick='changePassFinal(\"" + tbl + "\",\"" + tr + "\");'>ОК</button></div><div class='col-sm-2 col-sm-offset-1'><button class='btn btn-warning' onclick='delOverley();'>Отмена</button></div></div></div>";

    myConfirm(massconfirm, '')
   // var param=document.getElementById('users').massiv;
   // param.tbl='users2';param.shuba=1;
  //  console.log(JSON.stringify(param));
  //  var men=new createWindow(''); 	men.wincreate(param);
   // return false;
}

function changePassFinal(tbl,tr) {
    var pass1=$('#password1').val();
    var pass2=$('#password2').val();
    if(pass1==pass2) {$("#errorsave").html('');}
    else{$("#errorsave").html('пароли не одинаковые');return false;}
    var elem=document.getElementById(tbl);
    var param=document.getElementById(tbl).massiv;
    //var tr=$(elem).find('tr').eqattr('id');
    param.pssw=pass1;
    var data='record='+tr+'&data='+JSON.stringify(param);
    // console.log(data);
    $.ajax({
        type: "POST",   url: "mod/psswsave.php",   data: data,
        success: function(data){
            //   console.log( "Прибыли данные: " + data );
            //$("#errorsave").html(data);
            var mass=datPars(data);
            if (mass[0]==1) {location.reload();
            }
            if (mass[0]==0) { $("#errorsave").html(mass[1]); }

        }
    });
    delOverley();
}
// ---download and upload elements------------
function downloadElem(){ let perem='<div>Загрузить данные по элементам?</div>';
    perem+='<div class="col-sm-3 col-sm-offset-3"><button class="btn btn-success" onclick="makeElem(0);">OK</button></div>';
    perem+='<div class="col-sm-3"><button class="btn btn-warning" onclick="{delOverley();}">Отмена</button></div>';
 myConfirm(perem, '');}
function uploadElem(){let perem='<div>Выгрузить данные по элементам?</div>';
    perem+='<div class="col-sm-3 col-sm-offset-3"><button class="btn btn-success" onclick="makeElem(1);">OK</button></div>';
    perem+='<div class="col-sm-3"><button class="btn btn-warning" onclick="{delOverley();}">Отмена</button></div>';
    myConfirm(perem, '');}
function makeElem(cheker){
    let param=document.getElementById('position');
    if ($(param).css('display')=='none' && !cheker) {console.log('No elements'); return false;}
     param=document.getElementById('position').massiv;
    param.direct=cheker; console.log(param);
    $.ajax({
        type: "POST",   url: "mod/makeelem.php",   data: 'data='+JSON.stringify(param),
        success: function(data){
               console.log( "Прибыли данные: " + data );
            //$("#errorsave").html(data);
            var mass=datPars(data);
            if (mass[0]==1) {location.reload();
            }
         //   if (mass[0]==0) { $("#errorsave").html(mass[1]); }

        }
    });

}