import SelectMail from "../Command/Out/SelectMail";

export default class Navigation {
    constructor() {
        this.title = document.querySelector('head title').textContent;

        this.items = {};
    }

    start(connection) {
        if (location.pathname === '/') {
            this.push({type: 'home'}, this.title, '/');
            return;
        }

        const pattern = /^\/(\d+)\/([^\/]+)\/([^\/]+)\/(.*)$/;

        if (!pattern.test(location.pathname)) {
            return;
        }

        const matches = location.pathname.match(pattern);

        connection.send(new SelectMail(matches[3]));
    }

    isDeleted(id) {
        return this.items.hasOwnProperty(id) && this.items[id] === false;
    }

    delete(id) {
        this.items[id] = false;

        history.replaceState({type: 'home'}, this.title, '/');
    }

    openMail(info) {
        this.items[info.id] = !info.deleted;

        this.push({type: 'mail', data: info}, info.subject + ' | ' + this.title, '/0/uncategorized/' + info.id + '/' + this.slugify(info.subject));
    }

    push(data, title, url) {
        history.pushState(data, title, url);

        document.querySelector('head title').textContent = title;
    }

    setTitle(title) {
        document.querySelector('head title').textContent = title + ' | ' + this.title;
    }

    resetState() {
        history.replaceState({type: 'home'}, this.title, '/');
        this.resetTitle();
    }

    resetTitle() {
        document.querySelector('head title').textContent = this.title;
    }

    slugify(text) {
        // https://gist.github.com/mathewbyrne/1280286
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
    }
}
