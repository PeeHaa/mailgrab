export default class Project {
    constructor(project) {
        this.addToDom();

        this.element = document.querySelector('nav#projects li:last-child');

        this.addId(project.id);
        this.addName(project.name);

        this.element.classList.add('active');
    }

    addToDom() {
        const container = document.querySelector('nav#projects ul');
        const template  = document.getElementById('new-project');
        const item      = document.importNode(template.content, true);

        container.append(item);
    }

    addId(id) {
        this.element.dataset.id = id;
    }

    addName(subject) {
        this.element.appendChild(document.createTextNode(subject));
    }

    addUnread() {
        const counter = this.element.querySelector('span');

        counter.textContent = parseInt(counter.textContent, 10) + 1;
    }

    updateUnread() {
        const counter = this.element.querySelector('span');

        counter.textContent = document.querySelectorAll('nav#messages .new').length;
    }
}
