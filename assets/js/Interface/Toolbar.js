export default class Toolbar {
    constructor() {
        this.toolbar = document.querySelector('header ul');
    }

    openMail(info) {
        this.toolbar.dataset.id = info.id;

        this.deactivateAll();

        this.toolbar.querySelector('[data-type="text"]').classList.add('active');

        this.toolbar.classList.add('active');
    }

    openSource() {
        this.deactivateAll();

        this.toolbar.querySelector('[data-type="source"]').classList.add('active');
    }

    deactivateAll() {
        [].forEach.call(this.toolbar.querySelectorAll('.active'), function(e) {
            e.classList.remove('active');
        });
    }
}
