export default class Status {
    constructor() {
        this.element = document.querySelector('header span');
    }

    disconnect() {
        this.clear();

        this.element.classList.add('disconnected');

        this.addLabel('Disconnected');
    }

    reconnect() {
        this.clear();

        this.element.classList.add('connecting');

        this.addLabel('Connecting');
    }

    connect() {
        this.clear();
    }

    clear() {
        this.element.classList.remove('connecting', 'disconnected', 'connecting');

        while (this.element.firstChild) {
            this.element.removeChild(this.element.firstChild)
        }
    }

    addLabel(text) {
        const label = document.createTextNode(text);

        this.element.appendChild(label);
    }
}
