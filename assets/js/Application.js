import Connection from './WebSocket/Connection';
import CommandProcessor from './Command/Processor';
import Interface from './Interface/Interface';

import Init from './Command/Out/Init';

export default class Application {
    constructor(url) {
        this.connection       = new Connection(url);
        this.commandProcessor = new CommandProcessor({
            newMail: this.onNewMail.bind(this)
        });
        this.gui              = new Interface();
    }

    run() {
        this.connection.connect(() => {
            this.connection.send(new Init().stringify());
        }, this.commandProcessor.process.bind(this.commandProcessor));
    }

    onNewMail(data) {
        this.gui.addMails(data.mails);
    }
}
