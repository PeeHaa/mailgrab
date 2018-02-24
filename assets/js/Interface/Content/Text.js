export default class Text {
    constructor(content) {
        this.addToDom(() => {
            this.element = document.querySelector('iframe').contentWindow.document;

            const pre = this.element.createElement('pre');

            pre.textContent = content;

            this.element.querySelector('body').appendChild(pre);
        });
    }

    addToDom(callback) {
        const container = document.querySelector('main');
        const iframe    = document.createElement('iframe');

        container.appendChild(iframe);

        iframe.addEventListener('load', callback);
    }
}
