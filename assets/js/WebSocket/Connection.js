export default class Connection {
    constructor(url) {
        this.url    = url;
        this.socket = null;
    }

    connect(onConnecting, onOpen, onClose, onMessage) {
        onConnecting();

        this.socket = new WebSocket(this.url);

        this.socket.addEventListener('open', onOpen);
        this.socket.addEventListener('close', () => {
            onClose();

            setTimeout(this.connect.bind(this, onConnecting, onOpen, onClose, onMessage), 5000);
        });
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
console.log(message);
        this.socket.send(message.stringify());
    }
}
