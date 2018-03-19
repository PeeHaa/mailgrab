export default class Status {
    constructor() {
        this.element = document.querySelector('header span');
    }

    disconnect() {
        return;
        this.element.classList.remove('connecting');
        this.element.classList.add('disconnected');

        this.element.setAttribute('title', 'Disconnected');
    }

    reconnect() {
        return;
        this.element.classList.remove('disconnected');
        this.element.classList.add('connecting');

        this.element.setAttribute('title', 'Connecting');
    }

    connect() {
        return;
        this.element.classList.remove('disconnected');
        this.element.classList.remove('connecting');

        this.element.removeAttribute('title');
    }
}
