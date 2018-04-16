export default class Html {
    constructor(content) {
        this.addToDom(() => {
            console.warn('ADDING HTML TO DOM');
            this.element = document.querySelector('iframe').contentWindow.document;

            const body = new DOMParser().parseFromString(content, 'text/html');

            this.element.replaceChild(body.querySelector('html'), this.element.querySelector('html'));

            this.fixLinkTargets();
        });
    }

    addToDom(callback) {
        console.warn('ADDING TO DOM');
        const container = document.querySelector('main');
        const iframe    = document.createElement('iframe');

        iframe.addEventListener('load', callback);

        container.appendChild(iframe);
    }

    fixLinkTargets() {
        [].forEach.call(this.element.querySelectorAll('a:not([target="_blank"])'), function(link) {
            link.setAttribute('target', '_blank');
        });
    }
}
