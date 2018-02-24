import Connection from './WebSocket/Connection';
import CommandProcessor from './Command/Processor';
import Interface from './Interface/Interface';

import {targetByTagName} from './Util/util';

import Init from './Command/Out/Init';
import SelectMail from './Command/Out/SelectMail';
import GetText from "./Command/Out/GetText";
import GetHtml from "./Command/Out/GetHtml";
import GetSource from './Command/Out/GetSource';

export default class Application {
    constructor(url) {
        this.connection       = new Connection(url);
        this.commandProcessor = new CommandProcessor({
            newMail: this.onNewMail.bind(this),
            mailInfo: this.onMailInfo.bind(this),
            text: this.onText.bind(this),
            html: this.onHtml.bind(this),
            source: this.onSource.bind(this)
        });
        this.gui              = new Interface();

        this.addEventListeners();
    }

    run() {
        this.connection.connect(() => {
            this.connection.send(new Init());
        }, this.commandProcessor.process.bind(this.commandProcessor));
    }

    onNewMail(data) {
        this.gui.addMails(data.mails);
    }

    onMailInfo(data) {
        this.gui.openMail(data.info);
    }

    onText(data) {
        this.gui.openText(data.text);
    }

    onHtml(data) {
        this.gui.openHtml(data.html);
    }

    onSource(data) {
        this.gui.openSource(data.source);
    }

    addEventListeners() {
        document.querySelector('nav#messages ul').addEventListener('click', (e) => {
            const element = targetByTagName(e.target, 'li');

            if (!element) return;

            this.connection.send(new SelectMail(element.dataset.id));
        });

        document.querySelector('header [data-type="text"]').addEventListener('click', (e) => {
            this.connection.send(new GetText(e.target.parentNode.dataset.id));
        });

        document.querySelector('header [data-type="html"]').addEventListener('click', (e) => {
            this.connection.send(new GetHtml(e.target.parentNode.dataset.id));
        });

        document.querySelector('header [data-type="source"]').addEventListener('click', (e) => {
            this.connection.send(new GetSource(e.target.parentNode.dataset.id));
        });
    }
}
