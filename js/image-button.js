(function() {
    tinymce.PluginManager.add('imgbutton', function( editor, url ) {
        editor.addButton( 'imgbutton', {
            text: 'Insert Image',
            icon: false,
			onclick: function() {
				jQuery('#mceu_12-button').click();
			}
        });
    });
})();