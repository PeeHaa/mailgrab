import Mail from './NavBar/Mail';

export default class NavBar {
    constructor() {
        this.mails = {};

        document.querySelector('#messages .search input').addEventListener('keyup', this.search.bind(this));
        document.querySelector('#messages .search input').addEventListener('keypress', this.search.bind(this));
        document.querySelector('#messages .search input').addEventListener('paste', this.search.bind(this));
        document.querySelector('#messages .search input').addEventListener('input', this.search.bind(this));
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

    delete(id) {
        this.mails[id].delete();

        delete this.mails[id];
    }

    markAsRead(id) {
        this.mails[id].markAsRead();
    }

    reset() {
        Object.keys(this.mails).forEach((id) => {
            this.mails[id].deactivate();
        });
    }

    search(e) {
        Object.keys(this.mails).forEach((key)=> {
            this.mails[key].filter(e.target.value.toLowerCase());
        });
    }
}
