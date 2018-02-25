import Command from './Command';

export default class RefreshMail extends Command {
    constructor(id) {
        super('refreshMail', {id: id});
    }
}
