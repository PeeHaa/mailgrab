export default class Toolbar {
    constructor(callback) {
        this.callback = callback;

        this.toolbar = document.querySelector('header ul');

        this.attachEventListeners();
    }

    activate(subject) {
        this.toolbar.classList.add('active');
    }

    deactivate() {
        this.toolbar.classList.remove('active');
    }

    attachEventListeners() {
        document.querySelector('header ul').addEventListener('click', (e) => {
            this.callback(this.getNavigationType(e.target));
        });
    }

    getNavigationType(target) {
        let currentNode = target;

        do {
            if (currentNode.tagName === 'LI') {
                if ('type' in currentNode.dataset) {
                    return currentNode.dataset.type;
                }

                return false;
            }
        } while (currentNode = currentNode.parentNode);

        return false;
    }
}
