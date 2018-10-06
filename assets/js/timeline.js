require('./app.js');

const $ = require('jquery');

console.log('Loaded timeline file');

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
