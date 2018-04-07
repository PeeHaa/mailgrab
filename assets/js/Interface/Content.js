import Info from './Content/Info';
import Text from './Content/Text';
import Html from './Content/Html';
import HtmlWithoutImages from "./Content/HtmlWithoutImages";
import Source from './Content/Source';

export default class Content {
    constructor() {
        this.container = document.querySelector('main');
    }

    setConfig(config) {
        if (!document.querySelector('[data-field="hostname"]')) {
            return;
        }

        document.querySelector('.intro').style.display = 'block';
        document.querySelector('[data-field="hostname"]').textContent = config.hostname;
        document.querySelector('[data-field="smtpport"]').textContent = config.smtpport;
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

    openHtmlWithoutImages(source) {
        this.clear();

        new HtmlWithoutImages(source);
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
