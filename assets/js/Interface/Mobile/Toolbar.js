export default class Toolbar {
    constructor() {
        this.isMobile = false;

        this.expander    = document.querySelector('header .expander');
        this.title       = document.querySelector('header h1');
        this.hamburger   = document.querySelector('header .hamburger');
        this.toolbar     = document.querySelector('header > ul');
        this.closeButton = document.querySelector('header .mobile-close');

        this.hamburger.addEventListener('click', this.showToolbar.bind(this));
        this.closeButton.addEventListener('click', this.hideToolbar.bind(this));
    }

    enableMobile() {
        this.isMobile = true;
    }

    disableMobile() {
        this.isMobile = false;

        this.hideToolbar();
    }

    showToolbar() {
        if (!this.isMobile) {
            return;
        }

        this.expander.classList.add('menu-expanded');
        this.title.classList.add('menu-expanded');
        this.hamburger.classList.add('menu-expanded');
        this.toolbar.classList.add('menu-expanded');
    }

    hideToolbar() {
        this.expander.classList.remove('menu-expanded');
        this.title.classList.remove('menu-expanded');
        this.hamburger.classList.remove('menu-expanded');
        this.toolbar.classList.remove('menu-expanded');
    }
}
