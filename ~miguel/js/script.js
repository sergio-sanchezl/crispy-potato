/**
 * Variable para saber si se trata de un dispositivo
 * con pantalla táctil o no.
 */
var pantallaTactil = 'ontouchstart' in window || navigator.msMaxTouchPoints;

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
	$(".boton_menu").click (function () {

		$(".boton_menu, .menu").toggleClass ("desplegado");
	});
/*
	if (pantallaTactil) {

		$("html").on ("swipe", cambiarSecciones ());
	}
*/
});
