export default class Connection {
    constructor(url) {
        this.url    = url;
        this.socket = null;
    }

    connect(onOpen, onMessage) {
        this.socket = new WebSocket(this.url);

        this.socket.addEventListener('open', onOpen);
        this.socket.addEventListener('message', (e) => {
            const message = JSON.parse(e.data);
console.log(message);
            const command = message.payload.command;

            delete message.payload.command;

            const payload = message.payload;

            onMessage(command, payload);
        });
    }

    send(message) {
        console.log('%c Out: ' + message, 'background: #222; color: #bada55');
        this.socket.send(message);
    }
}
