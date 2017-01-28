/* Opciones para el editor de texto empotrado */
CKEDITOR.plugins.addExternal ('markdown', '/home/foo-manroot/Documents/web/crispy-potato/ckeditor/plugins/markdown/', 'plugin.js');

CKEDITOR.replace('editor', {
	skin: 'moono-dark,/home/foo-manroot/Documents/web/crispy-potato/ckeditor/skins/moono-dark/',
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
	extraPlugins: 'markdown'
});

CKEDITOR.on('instanceReady', function(e) {
	// First time
	e.editor.document.getBody().setStyle('background-color', '#e0e0e0');
	// in case the user switches to source and back
	e.editor.on('contentDom', function() {
		e.editor.document.getBody().setStyle('background-color', '#e0e0e0');
	});
});
