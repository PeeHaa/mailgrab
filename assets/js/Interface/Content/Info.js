const moment = require('moment');

export default class Info {
    constructor(mail) {
        this.addToDom();

        this.element = document.querySelector('main .info');

        this.addFrom(mail.from);
        this.addTo(mail.to);
        this.addCc(mail.cc);
        this.addBcc(mail.bcc);
        this.addSubject(mail.subject);
        this.addTimestamp(mail.timestamp);
    }

    addToDom() {
        const container = document.querySelector('main');
        const template  = document.getElementById('mail-info');
        const item      = document.importNode(template.content, true);

        container.prepend(item);
    }

    addFrom(from) {
        this.element.querySelectorAll('td')[0].textContent = from;
    }

    addTo(to) {
        this.element.querySelectorAll('td')[1].textContent = to;
    }

    addCc(cc) {
        if (cc === null) {
            return;
        }

        this.element.querySelectorAll('td')[2].textContent = cc;
    }

    addBcc(bcc) {
        if (bcc === null) {
            return;
        }

        this.element.querySelectorAll('td')[3].textContent = bcc;
    }

    addSubject(subject) {
        this.element.querySelectorAll('td')[4].textContent = subject;
    }

    addTimestamp(timestamp) {
        this.element.querySelector('time').dataset.timestamp = timestamp;
        this.element.querySelector('time').textContent = moment(timestamp).fromNow();
    }
}
