$(document).ready(function() {
	$('.si').iCheck({
		//checkboxClass: 'icheckbox_square',
		radioClass: 'iradio_flat-green',
		increaseArea: '50%' // optional
	});
	$('.no').iCheck({
		//checkboxClass: 'icheckbox_square',
		radioClass: 'iradio_flat-red',
		increaseArea: '50%' // optional
	});
	$('.rbtn-rta-ant-personal').on('ifChecked', function(event){
		var rbtn = $(this);
		/*para quitar validacion*/
		var contenedor = $(this).closest('.rta-ant-personal').first();
		$(contenedor).children('p').fadeOut('slow');
		$(contenedor).toggleClass('validar-rbtn-vacio',false);
		/*para quitar validacion*/
		if(rbtn.hasClass('si')){
			contenedor.siblings('div.observacion-ant-personal').first().toggleClass('hidden',false);
		}else{
			contenedor.siblings('div.observacion-ant-personal').first().toggleClass('hidden',true);
		}
	});
	$('.chkInforme').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		//radioClass: 'iradio_flat-red',
		increaseArea: '50%' // optional
	});
	$('.chkPaciente').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        increaseArea: '50%'
    });
    // init_radio_si_no();
});

function init_radio_si_no() {
	$('.si_previo').iCheck({
		//checkboxClass: 'icheckbox_square',
		radioClass: 'iradio_flat-green',
		increaseArea: '50%' // optional
	});
	$('.no_previo').iCheck({
		//checkboxClass: 'icheckbox_square',
		radioClass: 'iradio_flat-red',
		increaseArea: '50%' // optional
	});
}
