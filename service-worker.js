self.addEventListener('push', function(event) {
    const data = event.data.json();

    const options = {
        body: data.body,
        icon: 'view/assets/images/logo2.png', // Cambia esto según el icono que quieras mostrar
        actions: [
            { action: 'view', title: 'Ver', icon: 'view/assets/images/view.png' },
            { action: 'dismiss', title: 'Descartar', icon: 'view/assets/images/dismiss.png' }
        ],
        data: {
            url: data.url
        }
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    if (event.action === 'view') {
        clients.openWindow(event.notification.data.url);
    } else if (event.action === 'dismiss') {
        // Manejar la acción de descartar
    } else {
        clients.openWindow(event.notification.data.url);
    }
});
