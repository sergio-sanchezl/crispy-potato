/* Despliega el menú para móviles (si está presente) */
$(document).ready (function () {

	$(".boton_menu").click (function () {

		$(".boton_menu, .menu").toggleClass ("desplegado");
	});
});
