CKEDITOR.plugins.add('divmy',{
    init: function(editor){
       // var cmd = editor.addCommand('divmy', {
        //    exec:function(editor){
         //       editor.insertHtml('<div>'+editor.getSelection().getSelectedText()+'</div>' );} });
            var command = editor.addCommand('divmy', new CKEDITOR.dialogCommand('divmy'));
    command.modes = {wysiwyg:1, source:1};
    command.canUndo = true;
			
			
        
       // cmd.modes = { wysiwyg : 1, source: 1 };
        editor.ui.addButton('divmy',{
            label: 'Создать div',
            command: 'divmy',
            icon:'plugins/divmy/icons/div.png',
            toolbar: 'blocks,52'
        });
		 CKEDITOR.dialog.add('divmy', this.path + 'dialogs/divmy.js');
    }

});
