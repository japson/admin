CKEDITOR.plugins.add('linkinner', {
  init : function(editor) {
    var command = editor.addCommand('linkinner', new CKEDITOR.dialogCommand('linkinner'));
    command.modes = {wysiwyg:1, source:1};
    command.canUndo = true;

    editor.ui.addButton('Linkinner', {
      label : 'Вставить внутрен. ссылку',
      command : 'linkinner',
      icon: 'plugins/linkinner/images/cut.png'
    });

    CKEDITOR.dialog.add('linkinner', this.path + 'dialogs/linkinner.js');
  }
});