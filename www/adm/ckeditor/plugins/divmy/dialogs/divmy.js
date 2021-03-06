CKEDITOR.dialog.add('divmy', function(editor) {
  return {
    title : 'Вставить div с классом',
    minWidth : 400,
    minHeight : 100,
    onOk: function() {
      var cuttext = this.getContentElement( 'cut', 'cuttext').getInputElement().getValue();
        //console.log( JSON.stringify(cuttext.getInputElement()));
       // cuttext=this.getContentElement( 'cuttext');
     // this._.editor.insertHtml('<div class="play">' + cuttext + '</div>');
        this._.editor.insertHtml( '<div class="'+cuttext+'">'+editor.getSelection().getSelectedText()+'</div>' );
    },
    contents : [{
      id : 'cut',
      label : 'First Tab',
      title : 'First Tab',
      elements : [

          {
              id: 'elementStyle',
              type: 'select',
              style: 'width: 200px;',
              label: editor.lang.div.styleSelectLabel,
              'default': '',
              // Options are loaded dynamically.
              items: [
                  ['Select...', '']
              ],
              onChange: function() {
                  var dialog = CKEDITOR.dialog.getCurrent();
                  var curval = dialog.getContentElement( 'cut', 'elementStyle').getValue();
                 // console.log(curval);
                  var cuttext2 = dialog.getContentElement( 'cut', 'cuttext').setValue(curval);
                  //cuttext2.setValue('jkjhkjh');
              },
              onLoad: function(element) {
                  this.add('Заголовок', 'article_hd');
                  this.add('Цитата', 'article_ct');
                  this.add('Абзац', 'article_abz');
                  this.add('Шрифт меньше', 'article_less');
              }
          },

          {
        id : 'cuttext',
        type : 'text',
        //name: 'privet',
        label : 'Имя класса'
      },
	/*{
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
      }*/
      ]
    }]
  };
});