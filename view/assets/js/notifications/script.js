if ('serviceWorker' in navigator) {
    console.log('Service Worker es soportado.');
    if ('PushManager' in window) {
        console.log('Push Manager es soportado.');
        navigator.serviceWorker.register('/service-worker.js')
            .then(function(swReg) {
                console.log('Service Worker registrado:', swReg);

                // Aquí puedes suscribir al usuario a las notificaciones push
                subscribeUser(swReg);
            })
            .catch(function(error) {
                console.error('Error al registrar el Service Worker:', error);
            });
    } else {
        console.error('Push Manager no es soportado en este navegador.');
    }
} else {
    console.error('Service Worker no es soportado en este navegador.');
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

function subscribeUser(swReg) {
    const applicationServerKey = urlBase64ToUint8Array('BO6gY4P7chV23gMCiYcSI5d_jSbrfDHL_Ol5DmAZAv7LDYSbWxbKKbthyP3Sren1C_64SzUCzz9Du8STarG1CaI');
    swReg.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: applicationServerKey
    })
    .then(function(subscription) {
        // Enviar la suscripción al servidor
        return fetch('/subscribe.php', {
            method: 'POST',
            body: JSON.stringify(subscription),
            headers: {
                'Content-Type': 'application/json'
            }
        });
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la suscripción: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta del servidor:', data);
    })
    .catch(function(error) {
        console.error('Error al suscribir al usuario:', error);
    });
}
