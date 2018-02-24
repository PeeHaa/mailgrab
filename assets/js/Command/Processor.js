export default class Processor {
    constructor(handlers) {
        this.handlers = handlers;
    }

    process(command, payload) {
        if (!this.handlers.hasOwnProperty(command)) {
            return;
        }

        this.handlers[command](payload);
    }
}
