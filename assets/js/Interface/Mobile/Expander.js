export default class Expander {
    constructor() {
        this.expander = document.querySelector('header .expander');
        this.messages = document.querySelector('#messages');

        this.expander.addEventListener('click', this.clickHandler.bind(this));
    }

    clickHandler(e) {
        if (this.expander.classList.contains('expanded')) {
            this.collapse();

            return;
        }

        this.expand();
    }

    expand() {
        this.expander.classList.add('expanded');
        this.messages.classList.add('expanded');
    }

    collapse() {
        this.expander.classList.remove('expanded');
        this.messages.classList.remove('expanded');
    }
}