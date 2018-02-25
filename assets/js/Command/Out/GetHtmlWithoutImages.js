import Command from './Command';

export default class GetHtmlWithoutImages extends Command {
    constructor(id) {
        super('getHtmlWithoutImages', {id: id});
    }
}
