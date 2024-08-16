
function setCookie(cname, cvalue, exdays) {
	const d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	let expires = "expires="+ d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// let setCookie = (name, value, days) => {
// 	let expires = "";
// 	if (days) {
// 		let date = new Date();
// 		date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
// 		expires = "; expires=" + date.toUTCString();
// 	}
// 	document.cookie = name + "=" + (value || "") + ";" + expires + "; path=/";
// };

function getCookie(cname) {
	let name = cname + "=";
	let decodedCookie = decodeURIComponent(document.cookie);
	let ca = decodedCookie.split(';');
	for(let i = 0; i <ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return null;
}

// let getCookie = name => {
// 	let nameEQ = name + "=";
// 	let ca = document.cookie.split(";");
// 	for (var i = 0; i < ca.length; i++) {
// 		let c = ca[i];
// 		while (c.charAt(0) === " ") c = c.substring(1, c.length);
// 		if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
// 	}
// 	return null;
// };

//Calling cookie

// const close = document.getElementById("btnCerrarAvisoProteccion");
// const bannerAviso = document.getElementById("avisoProteccionDatos");

// close.addEventListener("click", function() {
// 	setCookie("closedAviso", "true", 16);
// 	bannerAviso.style.display = "none";
// });

// const closedAviso = getCookie("closedAviso");

// if (closedAviso === null) {
// 	bannerAviso.style.display = "block";
// 	// slideUp(bannerAviso);
// } else {
// 	bannerAviso.style.display = "none";
// 	// slideDown(bannerAviso);
// }