const moment = require('moment');

export default class Info {
    constructor(id, from, to, subject, timestamp) {
        this.id        = id;
        this.from      = from;
        this.to        = to;
        this.subject   = subject;
        this.timestamp = moment(timestamp);
    }

    render() {
        const container = document.querySelector('main');

        this.deleteContent(container);

        const template = document.getElementById('mail-info');
        const item     = document.importNode(template.content, true);

        container.prepend(item);

        const newItem = document.querySelector('main table');

        this.addFrom(newItem);
        this.addTo(newItem);
        this.addSubject(newItem);
        this.addTimestamp(newItem);
    }

    deleteContent(container) {
        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }
    }

    addFrom(newItem) {
        newItem.getElementsByTagName('td')[0].textContent = this.from;
    }

    addTo(newItem) {
        const to = [];
        for (const key in this.to) {
            if (!this.to.hasOwnProperty(key)) {
                continue;
            }

            to.push(this.to[key] + ' <' + key + '>');
        }

        newItem.getElementsByTagName('td')[1].textContent = to.join(', ');
    }

    addSubject(newItem) {
        newItem.getElementsByTagName('td')[2].textContent = this.subject;
    }

    addTimestamp(newItem) {
        newItem.getElementsByTagName('time')[0].textContent = this.timestamp.fromNow();
        newItem.getElementsByTagName('time')[0].dataset.timestamp = this.timestamp.toISOString();
    }
}
