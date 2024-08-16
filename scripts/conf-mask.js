$(function () {
	// $(".decimal").mask("00.00", { reverse: true });
	$(".money-decima").mask("#.##0", { reverse: true });
	$(".money").mask("000.000.000.000", { reverse: true });
	$(".percent").mask("000%", { reverse: true });
	$(".horaextraaprueba").mask("00", { reverse: true });
	// $(".horaextra").mask("00.0", {
	// 	reverse: true,
	// });
	$(".ip_address").mask("ZZZ.ZZZ.ZZZ.ZZZ", {
		translation: {
			Z: {
				pattern: /[0-9]/,
				optional: true,
			},
		},
	});
    $(".mac_address").mask("ZZ:ZZ:ZZ:ZZ:ZZ:ZZ", {
		translation: {
			Z: {
				pattern: /[a-zA-Z0-9]/,
				optional: true,
			},
		},
	});
});
