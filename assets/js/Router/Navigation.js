import SelectMail from "../Command/Out/SelectMail";

export default class Navigation {
    constructor() {
        this.title = document.querySelector('head title').textContent;
    }

    start(connection) {
        if (location.pathname === '/') {
            return;
        }

        const pattern = /^\/(\d+)\/([^\/]+)\/([^\/]+)\/(.*)$/;

        if (!pattern.test(location.pathname)) {
            return;
        }

        const matches = location.pathname.match(pattern);

        connection.send(new SelectMail(matches[3]));
    }

    openMail(info) {
        this.push(info, info.subject + ' | ' + this.title, '/0/uncategorized/' + info.id + '/' + this.slugify(info.subject));
    }

    push(data, title, url) {
        history.pushState(data, title, url);

        document.querySelector('head title').textContent = title;
    }

    setTitle(title) {
        document.querySelector('head title').textContent = title + ' | ' + this.title;
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
