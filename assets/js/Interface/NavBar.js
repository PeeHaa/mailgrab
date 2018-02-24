import Mail from './NavBar/Mail';

export default class NavBar {
    constructor() {
        this.mails = {};
    }

    addMails(mails) {
        mails.forEach((mail) => {
            this.mails[mail.id] = new Mail(mail);
        });
    }

    openMail(info) {
        Object.keys(this.mails).forEach((id) => {
            this.mails[id].deactivate();
        });

        this.mails[info.id].activate();
        this.mails[info.id].updateTime();
    }
}
