CKEDITOR.dialog.add('playlist', function(editor) {
  return {
    title : 'Вставить ссылку на песню',
    minWidth : 400,
    minHeight : 200,
    onOk: function() {
      var cuttext = this.getContentElement( 'cut', 'cuttext').getInputElement().getValue();
        //console.log( JSON.stringify(cuttext.getInputElement()));
       // cuttext=this.getContentElement( 'cuttext');
       // cuttext+=+' ...';
       // console.log(cuttext);
        // this._.editor.insertHtml('<div class="play">' + cuttext + '</div>');
        this._.editor.insertHtml( cuttext+' ...');
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
              type: 'hbox',  // hbox и vbox
              id: 'localPageOptions',
              children: [
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
      },
                  {
                      id : 'cuttext44',
                      type : 'button',
                      label : 'Очистить',
                      title: 'Button description',
                      accessKey: 'C',
                      disabled: false,
                      onClick: function()
                      {
                          $('.cke_dialog_contents').find('input[type=text]').val('');
                          //alert("Custom button clicked!");
                      }
                  },
          {
              id : 'cuttext33',
              type : 'button',
              label : 'Добавить все',
              title: 'Button description',
              accessKey: 'A',
              disabled: false,
              onClick: function()
              {   addAllSongs.init(('.tblselectpunkt tr'));
                  addAllSongs.select();
                  addAllSongs.why();

                  //alert("Custom button clicked!");
              }
          },

          ]}

          ]//elements
    }]
  };
});