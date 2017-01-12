/**
 * Semáforo para evitar que se aniden las transiciones
 * al subir y bajar muy rápido con el ratón (o las flechas).
 */
var sem = false;

/**
 * Muestra los elementos que estaban escondidos.
 */
function showElems () {
	/* Si el semáforo está ocupado, no hace nada */
	if (sem) {

		return;
	}

	/* Se bloquea el semáforo */
	sem = true;

	$(".main_title").addClass("change");
	$(".generated_by").addClass("change");

	/* Permite que el contenido aparezca suavemente.
	Cuando acabe la tarea, se libera el semáforo */
	$("#content").slideDown ("slow", function () {

		sem = false;
	});

	/* Hace el efecto del agua subiendo */
	$(".wave_bottom").addClass ("flow");
	$(".wave_middle").addClass ("flow");
	$(".wave").addClass ("flow");
}

/**
 * Elimina los elementos que se habían añadido en showElems()
 */
function hideElems () {
	/* Si el semáforo está ocupado, no hace nada */
	if (sem) {

		return;
	}

	/* Se bloquea el semáforo */
	sem = true;

	$(".main_title").removeClass("change");
	$(".generated_by").removeClass("change");

	/* El contenido se esconde barriendo hacia arriba
	Cuando acabe la tarea, se libera el semáforo */
	$("#content").slideUp ("slow", function () {

                sem = false;
        });

	/* Hace el efecto del agua bajando */
	$(".wave_bottom").removeClass ("flow");
	$(".wave_middle").removeClass ("flow");
	$(".wave").removeClass ("flow");
}

/**
 * Si se pulsa la tecla de navegación (flecha arriba y flecha abajo)
 * o la barra espaciadora, se activa el evento para hacer la transición
 * para mostrar el contenido.
 */
$('html').on ('keydown', function (e) {

	/* Se pulsan la barra espaciadora (32) o la flecha hacia abajo (40) */
	if (e.keyCode == 32 || e.keyCode == 40) {

		showElems ();
	}
	else if (e.keyCode == 38) {
		/* Se pulsa la flecha hacia arriba (38), se vuelve a la pantalla inicial */
		hideElems ();
	}
});

// detecta el movimiento de la rueda
$('html').on ('mousewheel DOMMouseScroll', function (e) {

       	var delta = (e.originalEvent.wheelDelta || -e.originalEvent.detail),
	    pos = $(document).scrollTop ();

	if (delta < 0) {
		// bajando
		showElems ();

	} else if (delta > 0) {
		// subiendo
		/* Sólo oculta los elementos si se encuentra en lo alto de la página */
		if (pos <= 1) {

			hideElems ();
		}
	}
});

/**
 * Efecto de las olas
 */
function waves () {

	var ocean = document.getElementById ("ocean"),
		waveWidth = 30,
		waveCount = $(window).width() / waveWidth,
		docFrag = document.createDocumentFragment();

	var wave,
	    wave_middle,
	    wave_bottom;

	for(var i = 0; i < waveCount; i++) {

		wave = document.createElement("div");
		wave.className += "wave";
		docFrag.appendChild(wave);
		wave.style.width = waveWidth;
		wave.style.left = i * waveWidth + "px";
		wave.style.webkitAnimationDelay = (i/101) + "s";

		wave_middle = document.createElement("div");
		wave_middle.className += "wave_middle";
		docFrag.appendChild(wave_middle);
		wave_middle.style.width = waveWidth;
		wave_middle.style.left = i * waveWidth + "px";
		wave_middle.style.webkitAnimationDelay = (i/83) + "s";

		wave_bottom = document.createElement("div");
		wave_bottom.className += "wave_bottom";
		docFrag.appendChild(wave_bottom);
		wave_bottom.style.width = waveWidth;
		wave_bottom.style.left = i * waveWidth + "px";
		wave_bottom.style.webkitAnimationDelay = (i/63) + "s";
	}

	ocean.appendChild (docFrag);
}

$(document).ready(function() {

	waves ();
});

/**
 * Función a ejecutar cuando se redimensione la ventana
 */
//$( window ).resize(function() {
//
//	/* Borra las olas antiguas y crea unas nuevas acordes con el tamaño actual */
//	$("#ocean").empty ();
//	waves ();
//});
