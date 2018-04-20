export default class Expander {
    constructor() {
        this.expander = document.querySelector('header .expander');

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
    }

    collapse() {
        this.expander.classList.remove('expanded');
    }
}
