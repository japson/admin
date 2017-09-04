

//table_auto=function() {$( 'table #section' ).cassette();};

table_auto=function createMass(element,data){
	this.elem=document.getElementById(element);
	this.elem.massiv=data;
	console.log(this.elem);
	
	}
	
function editNew(){
	doc_h ='400px'; doc_w ='540px';
	 //$(document).height();
	if(doc_h>650){doc_h="500px";} else{doc_h="300px";};
	// CKEDITOR.replace( 'textID',{}); 
	CKEDITOR.replace( 'textID',  { height: doc_h,// weight: doc_w, 
	//	extraPlugins: 'imageuploader, justify, filebrowserImageUploadUrl": "../ckeditor/plugins/iaupload.php',
		//extraPlugins: 'justify',
	//"extraPlugins": "imgbrowse",
	//	"filebrowserImageBrowseUrl": "../ckeditor/plugins/imgbrowse/imgbrowse.html",	//?imgroot=/img/imgnew
  "filebrowserImageUploadUrl": "ckeditor/plugins/iaupload.php"
}); 

//var elem=document.getElementsByClassName('cke_wysiwyg_frame cke_reset');
//elem.width='540px';
//$('.cke_wysiwyg_frame').css('width','540px');
//console.log(elem.width);
//$("#cke_1_contents").css('height','300px');
	}

	