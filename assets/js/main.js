require('./../scss/app.scss');

import Application from './Application';

new Application('ws://localhost:8000/ws').run();
