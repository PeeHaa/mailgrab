import NavBar from './NavBar';
import Projects from './Projects';
import Toolbar from './Toolbar';
import Content from './Content';
import Status from "./Status";
import Notification from "./Notification";
import Notifier from './Notifier';

import MobileExpander from './Mobile/Expander';
import MobileToolbar from './Mobile/Toolbar';

const moment = require('moment');

export default class Interface {
    constructor() {
        this.status   = new Status();
        this.projects = new Projects();
        this.navBar   = new NavBar();
        this.toolBar  = new Toolbar();
        this.content  = new Content();
        this.notifier = new Notifier();

        this.mobileExpander = new MobileExpander();
        this.mobileToolbar   = new MobileToolbar();

        this.updateMobileStatus(window.innerWidth);

        window.addEventListener('resize', (e) => {
            this.updateMobileStatus(window.innerWidth);
        });

        this.activeItem = null;

        setInterval(this.updateTimestamp.bind(this), 500);
    }

    updateMobileStatus(width) {
        if (width > 768) {
            this.mobileExpander.disableMobile();
            this.mobileToolbar.disableMobile();

            return;
        }

        this.mobileExpander.enableMobile();
        this.mobileToolbar.enableMobile();
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

    setConfig(config) {
        this.content.setConfig(config);
    }

    addMails(mails) {
        this.projects.addMails(mails);
        this.navBar.addMails(mails);

        this.notifier.send(mails[0]);
    }

    openMail(info) {
        this.navBar.openMail(info);
        this.toolBar.openMail(info);
        this.projects.openMail(info);
        this.content.openMail(info);

        this.activeItem = info.id;

        this.mobileToolbar.openMail();
        this.mobileExpander.collapse();
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
        this.content.clearAll();

        this.activeItem = null;

        this.mobileToolbar.closeMail();
        this.mobileExpander.expand();
    }

    deleteNotification(id) {
        this.navBar.delete(id);
        this.projects.deleteMail();

        if (this.activeItem === id) {
            new Notification('danger', 'Delete message', ['A different user deleted your currently active e-mail.']);

            this.toolBar.delete();
            this.content.clearAll();
        }
    }

    readNotification(id) {
        this.navBar.markAsRead(id);
        this.projects.markAsRead();
    }

    updateTimestamp() {
        document.querySelectorAll('time').forEach((time) => {
            time.textContent = moment(time.dataset.timestamp).fromNow();
        });
    }

    reset() {
        this.toolBar.delete();
        this.navBar.reset();
        this.content.clearAll();
    }
}
