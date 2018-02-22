require('./../scss/app.scss');

import Connection from './Connection';
import Processor from './Command/Processor';
import Interface from './Interface/Interface';

const commandProcessor = new Processor();

const connection = new Connection('ws://localhost:8000/ws', commandProcessor.process.bind(commandProcessor));
connection.connect();

const gui = new Interface(connection);
