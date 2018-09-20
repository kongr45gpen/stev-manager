require('./app.js');

const prism = require('prismjs');
const prismyaml = require('prismjs/components/prism-yaml');

console.log('Loaded events file');

// window.prism = prism
// window.toast = 7

prism.highlightAll();
