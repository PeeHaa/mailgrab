export default class Toolbar {
    constructor() {
        this.expander  = document.querySelector('header .expander');
        this.title     = document.querySelector('header h1');
        this.hamburger = document.querySelector('header .hamburger');
        this.toolbar   = document.querySelector('header > ul');

        this.hamburger.addEventListener('click', this.showToolbar.bind(this));
    }

    showToolbar() {
        this.expander.classList.add('menu-expanded');
        this.title.classList.add('menu-expanded');
        this.hamburger.classList.add('menu-expanded');
        this.toolbar.classList.add('menu-expanded');
    }
}
