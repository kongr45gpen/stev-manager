require('./app.js');

const prism = require('prismjs');
const prismyaml = require('prismjs/components/prism-yaml');

console.log('Loaded events/volunteers file');

prism.highlightAll();
