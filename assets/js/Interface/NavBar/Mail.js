const moment = require('moment');

export default class Mail {
    constructor(mail) {
        this.addToDom();

        this.element = document.querySelector('nav#messages li');

        this.addId(mail.id);
        this.addSubject(mail.subject);
        this.addTimestamp(mail.timestamp);
        this.setReadStatus(mail.read);
    }

    addToDom() {
        const container = document.querySelector('nav#messages ul');
        const template  = document.getElementById('new-mail');
        const item      = document.importNode(template.content, true);

        container.prepend(item);
    }

    addId(id) {
        this.element.dataset.id = id;
    }

    addSubject(subject) {
        this.element.querySelector('h4').appendChild(document.createTextNode(subject));
    }

    addTimestamp(timestamp) {
        this.element.querySelector('time').dataset.timestamp = timestamp;
        this.element.querySelector('time').textContent = moment(timestamp).fromNow();
    }

    setReadStatus(read) {
        if (read) {
            return;
        }

        this.element.classList.add('new');
    }

    activate() {
        this.element.classList.remove('new');
        this.element.classList.add('active');
    }

    deactivate() {
        this.element.classList.remove('active');
    }

    updateTime() {
        const timestamp = this.element.querySelector('time').dataset.timestamp;

        this.element.querySelector('time').textContent = moment(timestamp).fromNow();
    }

    delete() {
        this.element.parentNode.removeChild(this.element);
    }

    markAsRead() {
        this.element.classList.remove('new');
    }
}
