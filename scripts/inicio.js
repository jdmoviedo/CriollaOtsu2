$(document).ready(function() {
    //actualizarUltimaConexion();
});

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register(urlBase+'scripts/sw.js').then(registration => {
        console.log('Service Worker registrado con éxito:', registration);
        if (Notification.permission === 'default') {
            console.log("Sin Permisos aun");
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    console.log("Solicitar permiso");
                    subscribeUser(registration);
                }else{
                    console.log("No dio permiso");
                }
            });
        } else if (Notification.permission === 'granted') {
            subscribeUser(registration);
        } else {
            console.log('Permiso de notificaciones denegado');
        }
    }).catch(error => {
        console.error('Error al registrar el Service Worker:', error);
    });
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function subscribeUser(registration) {
    const applicationServerKey = urlBase64ToUint8Array('BMuL4Tdd0fSwg3ixxDK2A_Dmi74gFzQqqJgmEehNjJQaoHQe8wcxKFsxvRJi1BNMrAftNPah-4pEzyI2-AlO_BQ');
    registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: applicationServerKey
    }).then(subscription => {
        fetch(urlBase+'php/libraries/SaveSuscription.php', {
            method: 'POST',
            body: JSON.stringify(subscription),
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                console.log('Suscripción guardada con éxito');
            } else {
                console.error('Error al guardar la suscripción');
            }
        }).catch(error => {
            console.error('Error al enviar la suscripción:', error);
        });
    }).catch(error => {
        console.error('Error al suscribirse al Push Manager:', error);
    });
}