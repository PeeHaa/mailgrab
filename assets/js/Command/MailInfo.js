import Info from './../Interface/Content/Info';

export default class MailInfo {
    process(data) {
        new Info(data.id, data.from, data.to, data.subject, data.timestamp).render();

        if (document.querySelector('header li.active')) {
            document.querySelector('header li.active').classList.remove('active');
        }

        document.querySelector('header li[data-type="info"]').classList.add('active');
    }
}
