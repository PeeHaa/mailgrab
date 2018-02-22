export default class Connection {
    constructor(url, callback) {
        this.url      = url;
        this.callback = callback;

        this.socket = new WebSocket(this.url);
    }

    connect() {
        this.socket.addEventListener('message', this.processMessage.bind(this));
    }

    processMessage(e) {
        const message = JSON.parse(e.data);
console.log(message);
        this.callback(message.type, message.data);
    }

    send(message) {
        this.socket.send(JSON.stringify(message));
    }
}
