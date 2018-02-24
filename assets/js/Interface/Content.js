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

        if (info.hasText) {
            new Text(info.content);
        } else {
            new Html(info.content);
        }
    }

    openText(source) {
        this.clear();

        new Text(source);
    }

    openHtml(source) {
        this.clear();

        new Html(source);
    }

    openSource(source) {
        this.clear();

        new Source(source);
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
