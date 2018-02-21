const moment = require('moment');

export default class NewMail {
    process(data) {
        this.addNewItem();

        const newItem = document.querySelector('nav li');

        this.addSubject(newItem, data.subject);
        this.addTImestamp(newItem, data.timestamp)
    }

    addNewItem() {
        const container = document.querySelector('nav ul');
        const template  = document.getElementById('new-mail');
        const item      = document.importNode(template.content, true);

        container.prepend(item);
    }

    addSubject(newItem, subject) {
        newItem.getElementsByTagName('a')[0].appendChild(document.createTextNode(subject));
    }

    addTImestamp(newItem, timestamp) {
        newItem.getElementsByTagName('time')[0].dataset.timestamp = timestamp;
        newItem.getElementsByTagName('time')[0].textContent = moment(timestamp).fromNow();
    }
}
