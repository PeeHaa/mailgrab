import Command from './Command';

export default class SelectMail extends Command {
    constructor(id) {
        super('selectMail', {id: id});
    }
}
