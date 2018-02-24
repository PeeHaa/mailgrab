export default class Html {
    constructor(content) {
        this.addToDom(() => {
            this.element = document.querySelector('iframe').contentWindow.document;

            const body = new DOMParser().parseFromString(content, 'text/html');

            this.element.replaceChild(body.querySelector('html'), this.element.querySelector('html'));
        });
    }

    addToDom(callback) {
        const container = document.querySelector('main');
        const iframe    = document.createElement('iframe');

        container.appendChild(iframe);

        iframe.addEventListener('load', callback);
    }
}
