$(document).ready(function() {
	$('#rootwizard').bootstrapWizard({
		onTabShow: function(tab, navigation, index) {
			var $total = navigation.find('li').length;
			var $current = index+1;
			var $percent = ($current/$total) * 100;
			$('#rootwizard').find('.progress-bar').css({width:$percent+'%'});
		},
		onNext: function(tab, navigation, index) {
			console.log("Siguiente");
		},
		onPrevious: function(tab, navigation, index) {
			console.log("Anterior");
		},
		onFirst: function(tab, navigation, index) {
			console.log("Primero");
		},
		onLast: function(tab, navigation, index) {
			console.log("Último");
		},
		onTabClick: function(tab, navigation, index) {
			console.log("Clic en seccion \"tab\"");
		}
	});
});