export default class Toolbar {
    constructor() {
        this.toolbar = document.querySelector('header ul');
    }

    openMail(info) {
        this.toolbar.dataset.id = info.id;

        this.reset(info);

        if (info.hasHtml) {
            this.toolbar.querySelector('[data-type="html"]').classList.add('active');
        } else {
            this.toolbar.querySelector('[data-type="text"]').classList.add('active');
        }

        this.toolbar.classList.add('active');
    }

    reset(info) {
        this.toolbar.querySelector('[data-type="text"]').classList.remove('disabled');
        this.toolbar.querySelector('[data-type="html"]').classList.remove('disabled');
        this.toolbar.querySelector('[data-type="noimages"]').classList.remove('disabled');

        if (!info.hasText) {
            this.toolbar.querySelector('[data-type="text"]').classList.add('disabled');
        }

        if (!info.hasHtml) {
            this.toolbar.querySelector('[data-type="html"]').classList.add('disabled');
            this.toolbar.querySelector('[data-type="noimages"]').classList.add('disabled');
        }

        this.deactivateAll();
    }

    openText() {
        this.deactivateAll();

        this.toolbar.querySelector('[data-type="text"]').classList.add('active');
    }

    openHtml() {
        this.deactivateAll();

        this.toolbar.querySelector('[data-type="html"]').classList.add('active');
    }

    openHtmlWithoutImages() {
        this.deactivateAll();

        this.toolbar.querySelector('[data-type="noimages"]').classList.add('active');
    }

    openSource() {
        this.deactivateAll();

        this.toolbar.querySelector('[data-type="source"]').classList.add('active');
    }

    delete() {
        this.toolbar.querySelector('[data-type="text"]').classList.remove('disabled');
        this.toolbar.querySelector('[data-type="html"]').classList.remove('disabled');
        this.toolbar.querySelector('[data-type="noimages"]').classList.remove('disabled');

        this.deactivateAll();

        this.toolbar.dataset.id = '';
        this.toolbar.classList.remove('active');
    }

    deactivateAll() {
        [].forEach.call(this.toolbar.querySelectorAll('.active'), function(e) {
            e.classList.remove('active');
        });
    }
}
