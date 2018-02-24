import NavBar from './NavBar';
import Projects from './Projects';
import Toolbar from './Toolbar';
import Content from './Content';

const moment = require('moment');

export default class Interface {
    constructor() {
        this.projects = new Projects();
        this.navBar   = new NavBar();
        this.toolBar  = new Toolbar();

        setInterval(this.updateTimestamp.bind(this), 500);
    }

    addMails(mails) {
        this.projects.addMails(mails);
        this.navBar.addMails(mails);
    }

    openMail(info) {
        this.navBar.openMail(info);
        this.toolBar.openMail();
        this.projects.openMail(info);

        new Content(info, 'text');
    }

    updateTimestamp() {
        document.querySelectorAll('time').forEach((time) => {
            time.textContent = moment(time.dataset.timestamp).fromNow();
        });
    }
}
