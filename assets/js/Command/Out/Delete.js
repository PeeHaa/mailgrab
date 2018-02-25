import Command from './Command';

export default class Delete extends Command {
    constructor(id) {
        super('delete', {id: id});
    }
}
