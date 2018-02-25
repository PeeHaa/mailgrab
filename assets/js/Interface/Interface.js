import NavBar from './NavBar';
import Projects from './Projects';
import Toolbar from './Toolbar';
import Content from './Content';
import Status from "./Status";

const moment = require('moment');

export default class Interface {
    constructor() {
        this.status   = new Status();
        this.projects = new Projects();
        this.navBar   = new NavBar();
        this.toolBar  = new Toolbar();
        this.content  = new Content();

        this.activeItem = null;

        setInterval(this.updateTimestamp.bind(this), 500);
    }

    disconnect() {
        this.status.disconnect();
    }

    reconnect() {
        this.status.reconnect();
    }

    connect() {
        this.status.connect();
    }

    addMails(mails) {
        this.projects.addMails(mails);
        this.navBar.addMails(mails);
    }

    openMail(info) {
        this.navBar.openMail(info);
        this.toolBar.openMail(info);
        this.projects.openMail(info);
        this.content.openMail(info);

        this.activeItem = info.id;
    }

    openText(source) {
        this.toolBar.openText();
        this.content.openText(source);
    }

    openHtml(source) {
        this.toolBar.openHtml();
        this.content.openHtml(source);
    }

    openHtmlWithoutImages(source) {
        this.toolBar.openHtmlWithoutImages();
        this.content.openHtmlWithoutImages(source);
    }

    openSource(source) {
        this.toolBar.openSource();
        this.content.openSource(source);
    }

    delete(id) {
        this.navBar.delete(id);
        this.toolBar.delete();
        this.content.clear();

        this.activeItem = null;
    }

    deleteNotification(id) {
        this.navBar.delete(id);

        if (this.activeItem === id) {
            // todo: notify user if deleted item is currently active

            this.toolBar.delete();
            this.content.clearAll();
        }
    }

    updateTimestamp() {
        document.querySelectorAll('time').forEach((time) => {
            time.textContent = moment(time.dataset.timestamp).fromNow();
        });
    }
}
