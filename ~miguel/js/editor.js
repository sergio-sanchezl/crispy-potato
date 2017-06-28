
var endpoint = "/~miguel/articulos/subir_recurso.php"


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


/**
 * Función llamada automáticamente al abrir el diálogo para subir un archivo.
 */
function file_picker_cb (callback, value, meta)
{
	var input = document.createElement('input')

	input.setAttribute ('type', 'file')

	switch (meta.filetype)
	{
		case 'image':
			input.setAttribute ('accept', 'image/*')
			break;
		case 'media':
			input.setAttribute ('accept', 'video/*')
			break;
	}

	// Note: In modern browsers input[type="file"] is functional without
	// even adding it to the DOM, but that might not be the case in some older
	// or quirky browsers like IE, so you might want to add it to the DOM
	// just in case, and visually hide it. And do not forget do remove it
	// once you do not need it anymore.


	input.onchange = function () {
		var file = this.files[0]

		var reader = new FileReader ()
		reader.readAsDataURL (file)
		reader.onload = function () {
			// Note: Now we need to register the blob in TinyMCEs image blob
			// registry. In the next release this part hopefully won't be
			// necessary, as we are looking to handle it internally.
			var id = 'blobid' + (new Date ()).getTime ()
			var blobCache = tinymce.activeEditor.editorUpload.blobCache
			var base64 = reader.result.split(',')[1]
			var blobInfo = blobCache.create(id, file, base64)
			blobCache.add(blobInfo)

			// call the callback and populate the Title field with the file name
			callback (blobInfo.blobUri (), { title: file.name })
		}
	}

	input.click()
}

/**
 * Función llamada automáticamente al pulsar el botón de guardar.
 */
function guardar ()
{
	var editor = tinyMCE.activeEditor
	    , contenido = editor.getContent ()
	    , request = new XMLHttpRequest ()
	    , url = "guardar_art.php"
	    , data = new FormData ()
	    , titulo = document.getElementById ('editor_titulo').childNodes [1].value
	    , categ = document.getElementById ('editor_categ').childNodes [1].value

	request.open ("POST", url)

	request.onreadystatechange = function () {

		if (request.readyState == 4)
		{
			if (request.status == 200)
			{
				editor.notificationManager.open ({
					text: "Artículo guardado"
					, type: "success"
				})
			}
			else
			{
				editor.notificationManager.open ({
					text: "Erro al guardar el artículo"
					, type: "error"
				})
			}
		}
	}

	data.append ("datos", contenido)
	data.append ("titulo", titulo)
	data.append ("categ", categ)

	request.send (data)
}


/* Opciones para el editor de texto empotrado */
tinymce.init ({
	selector: '#editor',
	plugins: [
		'advlist',
		'anchor',
		'autolink',
		'charmap',
		'code',
		'codesample',
		'colorpicker',
		'contextmenu',
		'directionality',
		'emoticons',
		'fullscreen',
		'help',
		'hr',
		'image',
		'imagetools',
		'insertdatetime',
		'link',
		'lists',
		'media',
		'nonbreaking',
		'pagebreak',
		'paste',
		'preview',
		'print',
		'save',
		'searchreplace',
		'table',
		'template',
		'textcolor',
		'textpattern',
		'toc',
		'visualblocks',
		'visualchars',
		'wordcount',
	],
	/* Apariencia de las barras de herramientas */
	toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft '
			+ 'aligncenter alignright alignjustify | bullist numlist outdent '
			+ 'indent | link image media | save',
	toolbar2: 'print preview | forecolor backcolor emoticons '
			+ '| codesample help',
	/* Imágenes y archivos */
	images_upload_url: endpoint,
	file_picker_callback: file_picker_cb,
	file_picker_types: 'file image media',
	automatic_uploads: true,
	relative_urls: false,
	/* Opciones de guardado */
	save_onsavecallback: guardar,
	/* Otras opciones */
	height: height () * (2 / 3),
	content_security_policy: "default-src 'self';"
				+ "script-src 'self';"
				+ "connect-src 'self';"
				+ "img-src 'self' data: blob:;"
				+ "style-src 'self' 'unsafe-inline';"
				+ "font-src 'self';"
});
