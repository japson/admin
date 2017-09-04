CKEDITOR.dialog.add('playlist', function(editor) {
  return {
    title : 'Создать playlist',
    minWidth : 400,
    minHeight : 200,
    onOk: function() {
      var cuttext = this.getContentElement( 'cut', 'cuttext').getInputElement().getValue();
      this._.editor.insertHtml('<iktomi-cut>' + cuttext + '</iktomi-cut>');
    },
    contents : [{
      id : 'cut',
      label : 'First Tab',
      title : 'First Tab',
      elements : [{
        id : 'cuttext',
        type : 'text',
        label : 'Текст ссылки'
      },
	{
        id : 'cuttext',
        type : 'button',
        label : 'Текст ссылки',
		title: 'Button description',
               accessKey: 'C',
               disabled: false,
               onClick: function()
                  {
                     alert("Custom button clicked!");
                  }
      }]
    }]
  };
});