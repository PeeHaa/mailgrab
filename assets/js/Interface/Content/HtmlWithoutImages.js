export default class HtmlWithoutImages {
    constructor(content) {
        this.addToDom(() => {
            this.element = document.querySelector('iframe').contentWindow.document;

            const body = new DOMParser().parseFromString(content, 'text/html');

            this.element.replaceChild(body.querySelector('html'), this.element.querySelector('html'));

            this.breakImages();
            this.fixLinkTargets();
        });
    }

    addToDom(callback) {
        const container = document.querySelector('main');
        const iframe    = document.createElement('iframe');

        container.appendChild(iframe);

        iframe.addEventListener('load', callback);
    }

    fixLinkTargets() {
        [].forEach.call(this.element.querySelectorAll('a:not([target="_blank"])'), function(link) {
            link.setAttribute('target', '_blank');
        });
    }

    breakImages() {
        [].forEach.call(this.element.querySelectorAll('img'), function(image) {
            image.src = '';
        });
    }
}
