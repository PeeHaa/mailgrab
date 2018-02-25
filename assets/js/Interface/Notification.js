export default class Notification {
    constructor(type, title, messages) {
        this.addToDom();

        this.element = document.querySelector('body > .notification');

        this.setType(type);
        this.setTitle(title);
        this.addMessages(messages);

        this.element.querySelector('.heading i').addEventListener('click', () => {
            this.element.parentNode.removeChild(this.element);
        });
    }

    addToDom() {
        const template  = document.getElementById('notification');
        const item      = document.importNode(template.content, true);

        document.querySelector('body').appendChild(item);
    }

    setType(type) {
        this.element.classList.add(type);
    }

    setTitle(title) {
        this.element.querySelector('h2').textContent = title;
    }

    addMessages(messages) {
        const body = this.element.querySelector('.body');

        messages.forEach((message) => {
            const paragraph = document.createElement('p');

            paragraph.textContent = message;

            body.appendChild(paragraph);
        });
    }
}
