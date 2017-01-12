/**
 * Variable para saber si se trata de un dispositivo
 * con pantalla táctil o no.
 */
var pantallaTactil = 'ontouchstart' in window || navigator.msMaxTouchPoints;

/**
 * Tamaño máximo para usar el estilo "layout-mobile.css"
 */
var pantalla_pequeña = 640;

/**
 * Bandera para saber si se ha cambiado la posición de
 * #secciones
 */
var secc_cambiado = false;


/**
 * Despliega el menú de secciones si estaba oculto,
 * o lo oculta si estaba desplegado.
 */
function cambiarSecciones () {


	var oculto = $('#secciones').data ('oculto');

	if (oculto) {

		$('#secciones').animate ({left: '-1px'}, 500);
		$('#boton_secc').animate ({left: '160px'}, 550, function () {

			$('#boton_secc').addClass ('desplegado');
		});
	} else {

		$('#secciones').animate ({left: '-250px'}, 500);
		$('#boton_secc').animate ({left: '-60px'}, 400, function () {

			$('#boton_secc').removeClass ('desplegado');
		});
	}

	$('#secciones').data ('oculto', !oculto);
}

/* Despliega los paneles laterales para los móviles (si están presentes) */
$(document).ready (function () {

	/* El panel de la izquierda con las secciones comienza oculto */
	$('#secciones').data ('oculto', 'true');

	/* Botón para las secciones (panel oculto a la izquierda) */
	$('#boton_secc').click (function () {

		cambiarSecciones ();
	});

	/* Botón para el menú */
	$('.boton_menu').click (function () {

		$('.boton_menu, .menu').toggleClass ('desplegado');
	});

	/* Pequeña animación al pinchar sobre la flecha para volver arriba */
	$('#flecha_nav').click (function () {

		$('html, body').animate ({scrollTop: 0}, 600);
	});
/*
	if (pantallaTactil) {

		$("#boton_secc").on ("swipe", cambiarSecciones ());
	}
*/
});

/**
 * Detecta redimensiones de la ventana
 */
$(window).resize (function () {

	var pos = 0;

	if ($(window).width () >= pantalla_pequeña) {

		/* Si el menú de la izquierda estaba oculto, lo muestra */
		pos = $('#secciones').offset();

		if (pos.left < 0) {

			$('#secciones').css ({left: '0px'});
			$('#secciones').data ('oculto', false);
			secc_cambiado = true;
		}
	} else {

		/* Si se ha cambiado la posición de #secciones, se restaura */
		if (secc_cambiado) {

			$('#secciones').css ({left: '-250px'});
			$('#secciones').data ('oculto', true);

			secc_cambiado = false;
		}
	}
});

/**
 * Detecta el cambio en la posición dentro de la página (scrolling)
 */
$(window).scroll (function () {

	var pos = $(document).scrollTop (),
	    umbral = 170,
	    opacidad = 0;

	/* Si la barra está más abajo del umbral, muestra la flecha */
	if (pos >= umbral) {

		opacidad = (pos * 0.001);

		if (opacidad > 1) {

			opacidad = 1;
		}
	}

	$('#flecha_nav').css ('opacity', opacidad);
});
