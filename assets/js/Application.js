import Connection from './WebSocket/Connection';
import CommandProcessor from './Command/Processor';
import Interface from './Interface/Interface';

import {parentByTagName} from './Util/util';

import Init from './Command/Out/Init';
import SelectMail from './Command/Out/SelectMail';
import GetText from "./Command/Out/GetText";
import GetHtml from "./Command/Out/GetHtml";
import GetHtmlWithoutImages from "./Command/Out/GetHtmlWithoutImages";
import GetSource from './Command/Out/GetSource';

export default class Application {
    constructor(url) {
        this.connection       = new Connection(url);
        this.commandProcessor = new CommandProcessor({
            newMail: this.onNewMail.bind(this),
            mailInfo: this.onMailInfo.bind(this),
            text: this.onText.bind(this),
            html: this.onHtml.bind(this),
            htmlWithoutImages: this.onHtmlWithoutImages.bind(this),
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

    onHtmlWithoutImages(data) {
        this.gui.openHtmlWithoutImages(data.html);
    }

    onSource(data) {
        this.gui.openSource(data.source);
    }

    addEventListeners() {
        document.querySelector('nav#messages ul').addEventListener('click', (e) => {
            const element = parentByTagName(e.target, 'li');

            if (!element) return;

            this.connection.send(new SelectMail(element.dataset.id));
        });

        document.querySelector('header [data-type="text"]:not(.disabled)').addEventListener('click', (e) => {
            this.connection.send(new GetText(parentByTagName(e.target, 'ul').dataset.id));
        });

        document.querySelector('header [data-type="html"]').addEventListener('click', (e) => {
            if (parentByTagName(e.target, 'li').classList.contains('disabled')) {
                return;
            }

            this.connection.send(new GetHtml(parentByTagName(e.target, 'ul').dataset.id));
        });

        document.querySelector('header [data-type="noimages"]').addEventListener('click', (e) => {
            if (parentByTagName(e.target, 'li').classList.contains('disabled')) {
                return;
            }

            this.connection.send(new GetHtmlWithoutImages(parentByTagName(e.target, 'ul').dataset.id));
        });

        document.querySelector('header [data-type="source"]').addEventListener('click', (e) => {
            this.connection.send(new GetSource(parentByTagName(e.target, 'ul').dataset.id));
        });
    }
}
