import NavBar from './NavBar';
import Toolbar from './Toolbar';

export default class Interface {
    constructor(connection) {
        this.connection = connection;

        this.activeItem = null;

        this.navbar  = new NavBar(this.loadMail.bind(this));
        this.toolbar = new Toolbar(this.processToolbar.bind(this));
    }

    loadMail(id) {
        this.connection.send({
            type: 'mail-info',
            data: {
                id: id
            }
        });

        this.activeItem = id;

        this.toolbar.activate();
    }

    processToolbar(type) {
        if (type === false) {
            return;
        }

        switch (type) {
            case 'delete':
                this.connection.send({
                    type: 'delete',
                    data: {
                        id: this.activeItem
                    }
                });
                this.activeItem = null;
                this.toolbar.deactivate();
                this.navbar.delete();
                return;
        }
    }
}
