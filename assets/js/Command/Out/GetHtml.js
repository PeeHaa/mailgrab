import Command from './Command';

export default class GetHtml extends Command {
    constructor(id) {
        super('getHtml', {id: id});
    }
}
