require('./../scss/app.scss');

import Connection from './Connection';
import Processor from './Command/Processor';

const commandProcessor = new Processor();

new Connection('ws://localhost:8000/ws', commandProcessor.process.bind(commandProcessor)).connect();
