import Command from './Command';

export default class GetSource extends Command {
    constructor(id) {
        super('getSource', {id: id});
    }
}
