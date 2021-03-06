
CKEDITOR.dialog.add('linkinner', function(editor) {
  return {
    title : 'Вставить внутрен. ссылку',
    minWidth : 400,
    minHeight : 200,
    onOk: function() {
      var cuttext = this.getContentElement( 'cut2', 'cuttext2').getInputElement().getValue();
        //console.log( JSON.stringify(cuttext.getInputElement()));
       // cuttext=this.getContentElement( 'cuttext');
       // cuttext+=+' ...';
       // console.log(cuttext);
        // this._.editor.insertHtml('<div class="play">' + cuttext + '</div>');
        this._.editor.insertHtml( cuttext+' ...');
    },
    contents : [{
      id : 'cut2',
      label : 'First Tab',
      title : 'First Tab',
      elements : [
          {
              id : 'polevybora2',
              minHeight  : 200 ,
              type : 'html',
              html: '<div id="mybar2"></div>'
          },


          {
        id : 'cuttext2',
        type : 'text',
        //name: 'privet',
        label : 'Текст ссылки'
      },
          {
              type: 'hbox',  // hbox и vbox
              id: 'localPageOptions',
              children: [
	{
        id : 'cuttext222',
        type : 'button',
        label : 'Обновить...',
		title: 'Button description',
               accessKey: 'C',
               disabled: false,
               onClick: function()
                  {
                      findPages();
                      //alert("Custom button clicked!");
                  }
      },
                  {
                      id : 'cuttext442',
                      type : 'button',
                      label : 'Очистить',
                      title: 'Button description',
                      accessKey: 'C',
                      disabled: false,
                      onClick: function()
                      {
                          $('.cke_dialog_contents').find('input[type=text]').val('');
                         // this.reset();
                          //alert("Custom button clicked!");
                      }
                  },
          {
              id : 'cuttext332',
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
//dialogObj.reset();