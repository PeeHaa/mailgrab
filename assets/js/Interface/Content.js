import Info from './Content/Info';
import Text from './Content/Text';
import Html from './Content/Html';
import Source from './Content/Source';

export default class Content {
    constructor(info, type) {
        this.container = document.querySelector('main');

        this.clear();

        new Info(info);

        this.renderContent(type);
    }

    clear() {
        while (this.container.firstChild) {
            this.container.removeChild(this.container.firstChild);
        }
    }

    renderContent(type) {
        if (['text', 'html', 'source'].indexOf(type) === -1) {
            throw 'Type (' + type + ') is not valid';
        }

        if (type === 'text') {
            new Text('foo');
        } else if (type === 'html') {
            new Html('foo');
        } else if (type === 'source') {
            new Source('foo');
        }
    }
}
