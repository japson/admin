CKEDITOR.plugins.add('spanmy',{
    init: function(editor){
        var cmd = editor.addCommand('spanmy', {
            exec:function(editor){
                editor.insertHtml('<span>'+editor.getSelection().getSelectedText()+'</span>' );
            }
        });
        cmd.modes = { wysiwyg : 1, source: 1 };
        editor.ui.addButton('Span',{
            label: 'Создать span',
            command: 'spanmy',
            icon:'plugins/spanmy/icons/span.png',
            toolbar: 'blocks,51'
        });
    }

});
