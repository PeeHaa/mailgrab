export default class Toolbar {
    constructor() {
        this.toolbar = document.querySelector('header ul');
    }

    openMail() {
        this.toolbar.classList.add('active');
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
