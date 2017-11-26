CKEDITOR.plugins.add('playlist', {
  init : function(editor) {
    var command = editor.addCommand('playlist', new CKEDITOR.dialogCommand('playlist'));
    command.modes = {wysiwyg:1, source:1};
    command.canUndo = true;

    editor.ui.addButton('Playlist', {
      label : 'Вставить ссылку на песню',
      command : 'playlist',
      icon: 'plugins/playlist/images/cut.png'
    });

    CKEDITOR.dialog.add('playlist', this.path + 'dialogs/playlist.js');
  }
});