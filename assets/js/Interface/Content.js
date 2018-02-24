import Info from './Content/Info';

export default class Content {
    constructor(info, type) {
        this.container = document.querySelector('main');

        this.clear();

        new Info(info);
    }

    clear() {
        while (this.container.firstChild) {
            this.container.removeChild(this.container.firstChild);
        }
    }
}
