/* Opciones para el editor de texto empotrado */
CKEDITOR.plugins.addExternal ('markdown', '/ckeditor/plugins/markdown/', 'plugin.js');
CKEDITOR.plugins.addExternal ('filebrowser', '/ckeditor/plugins/markdown/', 'plugin.js');

CKEDITOR.replace('editor', {
	skin: 'moono-dark,/ckeditor/skins/moono-dark/',
	language: 'es',
	height: '50%',
	toolbarGroups: [
		{ name: 'clipboard', groups: [ 'undo', 'clipboard' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	],
	removeButtons: 'PasteFromWord,Preview,Source,PasteText,HiddenField,ImageButton,Button,Textarea,TextField,Radio,Checkbox,Form,Select,CopyFormatting,CreateDiv,ShowBlocks,Flash,Iframe',
	extraPlugins: 'markdown,filebrowser',
	filebrowserBrowseUrl: 'browse.php',
	filebrowserUploadUrl: 'subir_recurso.php'
});


CKEDITOR.on ('instanceReady', function(e) {
	/* Se modifica el color de fondo del editor para suavizar el contraste */
	e.editor.document.getBody().setStyle('background-color', '#e0e0e0');

	e.editor.on('contentDom', function() {
		e.editor.document.getBody().setStyle('background-color', '#e0e0e0');
	});
});


CKEDITOR.on ('dialogDefinition', function( ev ) {
	/* Se cambia el diálogo para subir imágenes */
	var dialogName = ev.data.name;
	var dialogDefinition = ev.data.definition;

	if ( dialogName == 'image' ) {
		/* Se elimina la pestaña de 'avanzado' */
		dialogDefinition.removeContents ('advanced');
	}
   });
