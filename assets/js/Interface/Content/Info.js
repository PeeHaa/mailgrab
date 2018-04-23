const moment = require('moment');

export default class Info {
    constructor(mail) {
        this.addToDom();

        this.element = document.querySelector('main .info');

        this.addFrom(mail.from);
        this.addTo(mail.to);
        this.addCc(mail.cc);
        this.addBcc(mail.bcc);
        this.addSubject(mail.subject);
        this.addTimestamp(mail.timestamp);
        this.addAttachments(mail.attachments);
    }

    addToDom() {
        const container = document.querySelector('main');
        const template  = document.getElementById('mail-info');
        const item      = document.importNode(template.content, true);

        container.prepend(item);
    }

    addFrom(from) {
        this.element.querySelectorAll('td')[0].textContent = from;
    }

    addTo(to) {
        this.element.querySelectorAll('td')[1].textContent = to;
    }

    addCc(cc) {
        if (cc === null) {
            return;
        }

        this.element.querySelectorAll('td')[2].textContent = cc;
    }

    addBcc(bcc) {
        if (bcc === null) {
            return;
        }

        this.element.querySelectorAll('td')[3].textContent = bcc;
    }

    addSubject(subject) {
        this.element.querySelectorAll('td')[4].textContent = subject;
    }

    addTimestamp(timestamp) {
        this.element.querySelector('time').dataset.timestamp = timestamp;
        this.element.querySelector('time').textContent = moment(timestamp).fromNow();
    }

    addAttachments(attachments) {
        const attachmentsContainer = this.element.querySelector('.attachments');

        if (!attachments.length) {
            attachmentsContainer.parentNode.removeChild(attachmentsContainer);

            return;
        }

        attachments.forEach(this.addAttachment.bind(this));

        const height = this.element.querySelector('.basic').clientHeight;

        attachmentsContainer.style.height = (height - 5 ) + 'px';
    }

    addAttachment(attachment) {
        const listItem = document.createElement('li');
        const icon     = document.createElement('i');
        const link     = document.createElement('a');

        icon.classList.add('fas', this.getAttachmentTypeIcon(attachment['content-type']));

        setTimeout(function() {
            link.setAttribute('href', location.href + '/attachment/' + attachment.id);
        }, 1500);

        const name = document.createTextNode(attachment.name);

        listItem.appendChild(icon);
        link.appendChild(name);
        listItem.appendChild(link);

        this.element.querySelector('.attachments ul').appendChild(listItem);
    }

    getAttachmentTypeIcon(type) {
        switch (type) {
            case 'application/vnd.kde.kword':
            case 'application/vnd.lotus-wordpro':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            case 'application/msword':
            case 'application/vnd.ms-word.document.macroenabled.12':
            case 'application/vnd.ms-word.template.macroenabled.12':
            case 'application/vnd.wordperfect':
            case 'application/vnd.sun.xml.writer':
            case 'application/vnd.sun.xml.writer.global':
            case 'application/vnd.sun.xml.writer.template':
                return 'fa-file-word';
            case 'video/3gpp':
            case 'video/3gpp2':
            case 'video/x-msvideo':
            case 'video/vnd.dece.hd':
            case 'video/vnd.dece.mobile':
            case 'video/vnd.uvvu.mp4':
            case 'video/vnd.dece.pd':
            case 'video/vnd.dece.sd':
            case 'video/vnd.dece.video':
            case 'video/h261':
            case 'video/h263':
            case 'video/h264':
            case 'video/x-ms-wm':
            case 'video/x-ms-wmv':
            case 'video/mpeg':
            case 'video/mp4':
            case 'application/mp4':
            case 'video/ogg':
            case 'video/webm':
            case 'video/quicktime':
                return 'fa-file-video';
            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.ms-powerpoint.addin.macroenabled.12':
            case 'application/vnd.ms-powerpoint.slide.macroenabled.12':
            case 'application/vnd.ms-powerpoint.slide.macroenabled.12':
            case 'application/vnd.ms-powerpoint.slideshow.macroenabled.12':
            case 'application/vnd.ms-powerpoint.template.macroenabled.12':
            case 'application/vnd.kde.kpresenter':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
            case 'application/vnd.openxmlformats-officedocument.presentationml.slide':
            case 'application/vnd.openxmlformats-officedocument.presentationml.slideshow':
            case 'application/vnd.openxmlformats-officedocument.presentationml.template':
            case 'application/vnd.oasis.opendocument.presentation':
            case 'application/vnd.oasis.opendocument.presentation-template':
                return 'fa-file-powerpoint';
            case 'application/pdf':
                return 'fa-file-pdf';
            case 'image/vnd.dxf':
            case 'image/bmp':
            case 'image/vnd.xiff':
            case 'image/gif':
            case 'image/x-icon':
            case 'image/jpeg':
            case 'image/vnd.adobe.photoshop':
            case 'image/png':
            case 'image/svg+xml':
            case 'image/tiff':
            case 'image/webp':
                return 'fa-file-image';
            case 'application/vnd.ms-excel':
            case 'application/vnd.ms-excel.addin.macroenabled.12':
            case 'application/vnd.ms-excel.sheet.binary.macroenabled.12':
            case 'application/vnd.ms-excel.template.macroenabled.12':
            case 'application/vnd.ms-excel.sheet.macroenabled.12':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.template':
            case 'application/vnd.oasis.opendocument.spreadsheet':
            case 'application/vnd.oasis.opendocument.spreadsheet-template':
                return 'fa-file-excel';
            case 'text/html':
            case 'application/java-archive':
            case 'application/java-vm':
            case 'application/javascript':
            case 'application/json':
                return 'fa-file-code';
            case 'audio/x-aac':
            case 'audio/x-aiff':
            case 'audio/x-ms-wma':
            case 'audio/midi':
            case 'audio/mpeg':
            case 'audio/mp4':
            case 'audio/ogg':
            case 'audio/webm':
            case 'audio/x-wav':
                return 'fa-file-audio';
            case 'application/vnd.android.package-archive':
            case 'application/x-bzip':
            case 'application/x-bzip2':
            case 'application/x-rar-compressed':
            case 'application/x-tar':
            case 'application/zip':
                return 'fa-file-archive';
            case 'text/plain':
                return 'fa-file-alt';
            default:
                return 'fa-file';
        }
    }
}
