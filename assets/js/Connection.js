export default class Connection {
    constructor(url, callback) {
        this.url      = url;
        this.callback = callback;
    }

    connect() {
        const socket = new WebSocket(this.url);

        socket.addEventListener('message', this.processMessage.bind(this));
    }

    processMessage(e) {
        const message = JSON.parse(e.data);
console.log(message);
        this.callback(message.type, message.data);
    }
}
