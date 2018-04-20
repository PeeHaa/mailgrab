export default class Source {
    constructor(source) {
        this.addToDom(() => {
            this.element = document.querySelector('iframe').contentWindow.document;

            const pre = this.element.createElement('pre');

            pre.textContent = source;

            this.element.querySelector('body').appendChild(pre);

            const style = this.element.createElement('style');

            style.type = 'text/css';
            style.innerHTML = 'pre {color: #a3a3a3;background: #292929;padding: 15px;white-space:pre-wrap;word-break: break-word;}';

            this.element.querySelector('head').appendChild(style);
        });
    }

    addToDom(callback) {
        const container = document.querySelector('main');
        const iframe    = document.createElement('iframe');

        iframe.addEventListener('load', callback);

        container.appendChild(iframe);
    }
}
