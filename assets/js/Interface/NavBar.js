export default class NavBar {
    constructor(callback) {
        this.callback = callback;

        this.attachEventListeners();
    }

    attachEventListeners() {
        document.querySelector('nav ul').addEventListener('click', (e) => {
            const item = this.getNavigationItem(e.target);

            if (item === false) {
                return;
            }

            this.activateItem(item);

            this.callback(item.dataset.id);
        });
    }

    getNavigationItem(target) {
        let currentNode = target;

        do {
            if (currentNode.tagName === 'LI') {
                return currentNode;
            }
        } while (currentNode = currentNode.parentNode);

        return false;
    }

    activateItem(item) {
        this.deactivate();

        item.classList.add('active');
        item.classList.remove('new');
    }

    deactivate() {
        if (document.querySelector('nav li.active')) {
            document.querySelector('nav li.active').classList.remove('active');
        }
    }

    delete() {
        const item = document.querySelector('nav li.active');

        item.parentNode.removeChild(item);
    }
}
