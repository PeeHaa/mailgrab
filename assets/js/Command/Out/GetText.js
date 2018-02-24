import Command from './Command';

export default class GetText extends Command {
    constructor(id) {
        super('getText', {id: id});
    }
}
