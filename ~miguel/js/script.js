/* Despliega los paneles laterales para los móviles (si están presentes) */
$(document).ready (function () {

	/* El panel de la izquierda con las secciones comienza oculto */
	$('#secciones').data ('oculto', 'true');

	$('#boton_secc').click (function () {

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
	});

	$(".boton_menu").click (function () {

		$(".boton_menu, .menu").toggleClass ("desplegado");
	});
});
