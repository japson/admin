/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
	// { name: 'alignment', items : [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },	
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		//{ name: 'CreateDiv' },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' },
        { name: 'pagerasdel' },
		{ name: 'Playlist' }
	];


	config.enterMode = CKEDITOR.ENTER_BR;  
	config.entities = false;
	config.basicEntities = false;
	//config.fillEmptyBlocks = false;	

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
	
	config.extraPlugins = 'imageuploader,justify,playlist,div,spanmy,pagerasdel,divmy';

    config.allowedContent = true;
    config.extraAllowedContent = 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};span(*)[*]{*};ul(*)[*]{*}';
};
CKEDITOR.dtd.$removeEmpty['span'] = false;
CKEDITOR.dtd.$removeEmpty['div'] = false;

/*CKEDITOR.dtd.$removeEmpty['play'] = false;
CKEDITOR.dtd['play']={};
      CKEDITOR.dtd.$empty['play']=1;
      CKEDITOR.dtd.$nonEditable['play']=1;
      CKEDITOR.dtd.$object['play']=1;
	   config.allowedContent = true;*/

//config.protectedSource.push(/<(span)[^>]*>.*<\/span>/ig);
/*
// ������� �
config.enterMode = CKEDITOR.ENTER_P;   // ������� <p></p>
// ������� �
config.enterMode = CKEDITOR.ENTER_DIV; // ������� <div></div>
// ������� �
config.enterMode = CKEDITOR.ENTER_BR;  // ������� <br/>

*/