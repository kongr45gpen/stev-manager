require('./app.js');

const RandExp = require('randexp');
const $ = require('jquery');

$(document).ready(function() {
    console.log('Loaded search file');

    const $placeholderOriginal = $("#js--example-original");
    const $placeholderReplaced = $("#js--example-replace");
    const $findInput = $("#js--search-query");
    const $replaceInput = $("#js--replace-query");
    const $form = $("#js--search-form");

    const $choiceRegular = $("#js--search-type-regular");
    const $choiceRegex = $("#js--search-type-regex");

    $(".js--search-updater").on('change input', function() {
        const value = $findInput.val();
        const replace = $replaceInput.val();

        let input, output;

        if ($choiceRegex.is(':checked')) {
            let randExp = new RandExp(value);

            // Remove symbols and add greek characters
            randExp.defaultRange.subtract(58, 64);
            randExp.defaultRange.subtract(34, 43);
            randExp.defaultRange.subtract(47, 47);
            randExp.defaultRange.subtract(91, 96);
            randExp.defaultRange.subtract(123, 126);
            randExp.defaultRange.add(913, 937);
            randExp.defaultRange.subtract(930, 930);
            randExp.defaultRange.add(945, 974);
            randExp.min = 5;
            randExp.max = 10;

            input = randExp.gen();

            if (input.size >= 50) {
                input = input.substring(0,50);
            }

            const regex = new RegExp(value);
            output = input.replace(regex, replace);
        } else {
            input = value;
            output = replace;
        }

        $placeholderOriginal.text(input);
        $placeholderReplaced.text(output);

    });

});

