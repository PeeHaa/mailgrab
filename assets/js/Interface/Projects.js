import Project from './Projects/Project';

export default class Projects {
    constructor() {
        this.projects = {
            '0': new Project({
                id: '0',
                name: 'Uncategorized',
            })
        };
    }

    addMails(mails) {
        mails.forEach((mail) => {
            // noinspection JSUnresolvedVariable
            if (!this.projects.hasOwnProperty(mail.project)) {
                // noinspection JSUnresolvedVariable
                this.projects.push(new Project(mail.project, 'New Project'));
            }

            if (!mail.read) {
                // noinspection JSUnresolvedVariable
                this.projects[mail.project].addUnread();
            }
        });
    }

    openMail(info) {
        this.projects[info.project].updateUnread();
    }

    deleteMail() {
        this.projects['0'].updateUnread();
    }
}
