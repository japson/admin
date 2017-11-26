CKEDITOR.dialog.add('playlist', function(editor) {
  return {
    title : 'Вставить ссылку на песню',
    minWidth : 400,
    minHeight : 200,
    onOk: function() {
      var cuttext = this.getContentElement( 'cut', 'cuttext').getInputElement().getValue();
        //console.log( JSON.stringify(cuttext.getInputElement()));
       // cuttext=this.getContentElement( 'cuttext');
     // this._.editor.insertHtml('<div class="play">' + cuttext + '</div>');
        this._.editor.insertHtml( cuttext );
    },
    contents : [{
      id : 'cut',
      label : 'First Tab',
      title : 'First Tab',
      elements : [
          {
              id : 'polevybora',
              minHeight  : 200 ,
              type : 'html',
              html: '<div id="mybar"></div>'
          },
          {
        id : 'cuttext',
        type : 'text',
        //name: 'privet',
        label : 'Текст ссылки'
      },
	{
        id : 'cuttext22',
        type : 'button',
        label : 'Обновить...',
		title: 'Button description',
               accessKey: 'C',
               disabled: false,
               onClick: function()
                  {
                      findSongs();
                      //alert("Custom button clicked!");
                  }
      }]
    }]
  };
});