const moment = require('moment');

export default class Mail {
    constructor(mail) {
        this.addToDom();

        const newItem = document.querySelector('nav#messages li');

        this.addId(newItem, mail.id);
        this.addSubject(newItem, mail.subject);
        this.addTimestamp(newItem, mail.timestamp);
        this.setReadStatus(newItem, mail.read);
    }

    addToDom() {
        const container = document.querySelector('nav#messages ul');
        const template  = document.getElementById('new-mail');
        const item      = document.importNode(template.content, true);

        container.prepend(item);
    }

    addId(newItem, id) {
        newItem.dataset.id = id;
    }

    addSubject(newItem, subject) {
        newItem.appendChild(document.createTextNode(subject));
    }

    addTimestamp(newItem, timestamp) {
        newItem.getElementsByTagName('time')[0].dataset.timestamp = timestamp;
        newItem.getElementsByTagName('time')[0].textContent = moment(timestamp).fromNow();
    }

    setReadStatus(newItem, read) {
        if (read) {
            return;
        }

        newItem.classList.add('new');
    }
}
