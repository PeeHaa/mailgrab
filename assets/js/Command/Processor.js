import Initialized from './Initialized';
import NewMail from './NewMail';

export default class Processor {
    process(type, data) {
        switch (type) {
            case 'initialized':
                Initialized.process();
                break;

            case 'new-mail':
                new NewMail().process(data);
                break;
        }
    }
}
