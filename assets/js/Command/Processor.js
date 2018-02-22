import Initialized from './Initialized';
import NewMail from './NewMail';

export default class Processor {
    constructor(gui) {
        this.gui = gui;
    }

    process(type, data) {
        switch (type) {
            case 'initialized':
                Initialized.process();
                break;

            case 'new-mail':
                new NewMail().process(data);
                break;

            case 'mail-info':
                break;
        }
    }
}
