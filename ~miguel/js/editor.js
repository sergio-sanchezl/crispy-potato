/**
 * Obtiene la dimensión Y de la ventana.
 * Sacado de https://stackoverflow.com/a/4987330
 */
function height ()
{
   return window.innerHeight
	  || document.documentElement.clientHeight
	  || document.body.clientHeight
	  || 0;
}


/* Opciones para el editor de texto empotrado */
tinymce.init ({
	selector: '#editor',
	plugins: [
		'advlist',
		'autolink',
		'lists',
		'link',
		'image',
		'charmap',
		'print',
		'preview',
		'hr',
		'anchor',
		'pagebreak',
		'searchreplace',
		'wordcount',
		'visualblocks',
		'visualchars',
		'code',
		'fullscreen',
		'insertdatetime',
		'media',
		'nonbreaking',
		'save',
		'table',
		'contextmenu',
		'directionality',
		'emoticons',
		'template',
		'paste',
		'textcolor',
		'colorpicker',
		'textpattern',
		'imagetools',
		'codesample',
		'toc',
		'help'
	],
	toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft '
			+ 'aligncenter alignright alignjustify | bullist numlist outdent '
			+ 'indent | link image',
	toolbar2: 'print preview media | forecolor backcolor emoticons '
			+ '| codesample help',
	height: height () * (2 / 3),
//	content_security_policy: "default-src 'self'"
});
