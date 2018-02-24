import NavBar from './NavBar';
import Projects from './Projects';
import Toolbar from './Toolbar';

const moment = require('moment');

export default class Interface {
    constructor() {
        this.projects = new Projects();
        this.navBar   = new NavBar();

        setInterval(this.updateTimestamp.bind(this), 10000);
    }

    addMails(mails) {
        this.projects.addMails(mails);
        this.navBar.addMails(mails);
    }

    updateTimestamp() {
        document.querySelectorAll('time').forEach((time) => {
            time.textContent = moment(time.dataset.timestamp).fromNow();
        });
    }
}
