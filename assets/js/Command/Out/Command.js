export default class Command {
    constructor(name, data) {
        this.name = name;
        this.data = data;
    }

    stringify() {
        return JSON.stringify(Object.assign({command: this.name}, this.data));
    }
}
