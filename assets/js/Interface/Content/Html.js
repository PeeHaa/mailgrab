export default class Html {
    constructor(mail) {
        this.addToDom(() => {
            this.element = document.querySelector('iframe').contentWindow.document;

            const body = new DOMParser().parseFromString('<html><head><style>body { background: red; }</style></head><body><h1>Title</h1></body></html>', 'text/html');

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
