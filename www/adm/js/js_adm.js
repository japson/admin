// JavaScript Document
function datPars(data){ //проверка ajax поступления22   
	var result=0;
	var answer='Problem on the server. Its not answer.';
	if (data.length>0) {
		//console.log( "Прибыли данные: " + JSON.parse(data) );
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
	 //console.log( "Прибыли данные: " + data );
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
	var men=new createWindow(''); 	men.wincreate(param);	
	}	
function createArticle(){
	var param=document.getElementById('post').massiv;
	var men=new createWindow(''); 	men.wincreate(param);	
	}
function createElem(){
	visualDir();return false;

    var param=document.getElementById('position').massiv;
    var men=new createWindow(''); 	men.wincreate(param);
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
var itog=perem._init();
//console.log(itog);
if (itog[0].length>0){$("#errform").html('!!!'+itog[0]);}
	else{$.ajax({
    	type: "POST",   url: "mod/winsave.php",   data: 'data='+ JSON.stringify(itog[1])+'&param='+tabl+massa,
		  success: function(data){
		// console.log( "Прибыли данные: " + data );
		// $("#errorsave").html(data);
	 	var mass=datPars(data);
	 	if (mass[0]==1) { location.reload(); }
	 	if (mass[0]==0) { $("#errform").html(mass[1]); }
  		}
		});	}
	}	

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
			case 'vyvod': tmp=$(element).find('input').prop("checked"); if(tmp){self.out[attr]=1;}else{self.out[attr]=0;}  ;break;
			case 'rol': self.out[attr]=$(element).find('option:selected').val();  ;break;
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
	var data='tbl='+tbl+'&record='+perem.idnom+'&data='+JSON.stringify(perem.out);
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
    console.log(param);
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
            console.log( "Прибыли данные: " + data );
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
	console.log(priem);
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
			//console.log (this.massconfirm);
	}
	
}

function changePictName(param){ // --- функция изменения картинок АЯКС ---------------------------
	var tmp=document.getElementById('reservdata').massiv;
	//console.log (tmp);
	if (param==0) {
		 $('#downformvvod').html(tmp.txt); delete document.getElementById('reservdata').massiv; $('.buttpictform').css('display','block');return false;}
	else{
			tmp=document.getElementById('delpictconfirm').massiv;
			var el=document.getElementById('namepictxt'); if(el) {tmp.text=$(el).val();}
		//console.log(JSON.stringify(tmp));
		$.ajax({
		type: "POST",   url: "mod/changephoto.php",   data: "data="+JSON.stringify(tmp),
 		 success: function(data){
			// console.log( "Прибыли данные: " + (data)  );
			 var mass=datPars(data);
			// console.log(tmp.kodpos);
			if (mass[0]==0) { $("#errform").html(mass[1]); }
  			 if (mass[0]==1) {
	 		  $('.Myconfirm ').html(mass[1]); 
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
	
	function visualDir() {
        var param=document.getElementById('position').massiv;
        console.log(param);
        $.ajax({
            type: "POST",   url: "mod/dirwatch.php",   data: "param="+JSON.stringify(param),
            success: function(data){
                  console.log( "Прибыли данные: " + data  ); //+ data
                data= JSON.parse(data);
               for(i=0;i<data.length;i++) {
                   console.log(data[i]);
               }
				return false;
                var mass=datPars(data);
                if (mass[0]==0) { $("#errorsave").html(mass[1]);}
                if (mass[0]==1) {$("#post").html(mass[1]);
                    $('a[id="editArticle"]').replaceWith('<a href="#" id="viewArticle" onClick="viewArticle(); return false;">Посмотреть статью...</a>');
                    editNew();
                }
                // location.reload();
            }// success
        });
    }
	
	
	
	
		 
/*var feilds=jQuery('.draggable');
        	var data;
        	data=new Object();
        	for (i=0; i<feilds.length; i++){
        		data['feilds['+i+'][value]']=jQuery(feilds[i]).text();
        		data['feilds['+i+'][top]']=jQuery(feilds[i]).css('top').replace("px","");
        		data['feilds['+i+'][left]']=jQuery(feilds[i]).css('left').replace("px","");
        		console.log(data);
        	};
			
function createMenu(param){
	$("#errorsave").html("");
	var ukaz=$('table').attr('id');
	$.ajax({
    type: "POST",   url: "mod/wincreate.php",   data: 'tbl='+ukaz+'&param='+param,
  success: function(data){
	// console.log( "Прибыли данные: " + data );
	// $("#errorsave").html(data);
	 var mass=datPars(data);
	 if (mass[0]==1) { myConfirm(mass[1], ''); }
	 if (mass[0]==0) { $("#errorsave").html(mass[1]); }
  		}
	});	
	
	}
			
			
			*/