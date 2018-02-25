export default class Notifier {
    constructor() {
        this.lastNotification = new Date();
    }

    send(info) {
        if (!this.isNotificationAllowed()) {
            return;
        }

        this.lastNotification = new Date();

        Notification.requestPermission().then(function(result) {
            if (result === 'denied') {
                return;
            }

            const notification = new Notification('New mail', {
                body: info.subject,
                tag: 'newMail',
                data: {id: info.id}
            });

            notification.addEventListener('click', (e) => {
                window.focus();
            });
        });
    }

    isNotificationAllowed() {
        return Math.abs(new Date().getTime() - this.lastNotification.getTime()) > 15000;
    }
}
