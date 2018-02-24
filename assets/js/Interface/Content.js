import Info from './Content/Info';
import Text from './Content/Text';
import Html from './Content/Html';
import Source from './Content/Source';

export default class Content {
    constructor() {
        this.container = document.querySelector('main');
    }

    openMail(info) {
        this.clearAll();

        new Info(info);

        new Text(info.text);
    }

    openSource(source) {
        this.clear();

        new Source(source);
    }

    renderContent(info, type) {
        if (['text', 'html', 'source'].indexOf(type) === -1) {
            throw 'Type (' + type + ') is not valid';
        }

        if (type === 'text') {
            new Text(info.text);
        } else if (type === 'html') {
            new Html('foo');
        } else if (type === 'source') {
            new Source('foo');
        }
    }

    clearAll() {
        while (this.container.firstChild) {
            this.container.removeChild(this.container.firstChild);
        }
    }

    clear() {
        const iframe = this.container.querySelector('iframe');

        if (iframe) {
            iframe.parentNode.removeChild(iframe);
        }
    }
}
