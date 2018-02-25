import SelectMail from "../Command/Out/SelectMail";

export default class Navigation {
    start(connection) {
        if (location.pathname === '/') {
            return;
        }

        const pattern = /^\/(\d+)\/([^\/]+)\/([^\/]+)\/(.*)$/;

        if (!pattern.test(location.pathname)) {
            console.log('NO MATCH!?');
            return;
        }

        const matches = location.pathname.match(pattern);

        connection.send(new SelectMail(matches[3]));
    }

    openMail(info) {
        this.push(info, info.subject + ' | MailGrab', '/0/uncategorized/' + info.id + '/' + this.slugify(info.subject));

        console.warn(location.pathname);
    }

    push(data, title, url) {
        history.pushState(data, title, url);

        document.querySelector('head title').textContent = title;
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
