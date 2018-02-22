import NavBar from './NavBar';
import Toolbar from './Toolbar';

const moment = require('moment');

export default class Interface {
    constructor(connection) {
        this.connection = connection;

        this.activeItem = null;

        this.navbar  = new NavBar(this.selectMail.bind(this));
        this.toolbar = new Toolbar(this.processToolbar.bind(this));

        setInterval(this.updateTimestamp.bind(this), 10000);
    }

    selectMail(id) {
        this.connection.send({
            type: 'mail-info',
            data: {
                id: id
            }
        });

        this.activeItem = id;

        this.toolbar.activate();
    }

    loadMail(mail) {

    }

    processToolbar(type) {
        if (type === false) {
            return;
        }

        switch (type) {
            case 'delete':
                this.processDelete();
                return;
        }
    }

    processDelete() {
        this.connection.send({
            type: 'delete',
            data: {
                id: this.activeItem
            }
        });
        this.activeItem = null;
        this.toolbar.deactivate();
        this.navbar.delete();

        const content = document.getElementsByTagName('main')[0];

        while (content.firstChild) {
            content.removeChild(content.firstChild);
        }
    }

    updateTimestamp() {
        document.querySelectorAll('time').forEach((time) => {
            console.log(time.dataset.timestamp);
            time.textContent = moment(time.dataset.timestamp).fromNow();
        });
    }
}
