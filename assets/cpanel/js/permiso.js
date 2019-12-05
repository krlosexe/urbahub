$(document).ready(function(){
	swal({
    title: '¡Ud. no tiene permiso para entrar en este modulo!',
    type: "warning",
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Aceptar!",
    closeOnConfirm: true
	},
	function(isConfirm){
	    history.back();
	});
});