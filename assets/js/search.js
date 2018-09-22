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

    const $statusText = $("#js--search-status");
    const $statusIcon = $("#js--search-status-icon");
    const $statusButton= $("#js--search-status-button");
    const $statusCounter = $("#js--search-status-count");

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

            // TODO: Handle errors and ignore them. Wrong JS regex might be good PHP regex.
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

        $form.submit();
    });

    $form.submit(function(e) {
        e.preventDefault();

        // Display stuff
        $statusText.html('Loading&hellip;');
        $statusIcon.removeClass('fa-times fa-check');
        $statusIcon.addClass('fa-spinner fa-spin');
        $statusButton.removeClass('btn-danger btn-success');
        $statusButton.addClass('btn-light');

        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),
            success: function (data) {
                $("#js--results-container").html(data);

                // Display stuff (reset)
                $statusText.html('Ready');
                $statusIcon.addClass('fa-check');
                $statusIcon.removeClass('fa-spinner fa-spin');
                $statusButton.removeClass('btn-light');
                $statusButton.addClass('btn-success');
                $statusCounter.text($("#js--results").attr('data-count'));
            },
            error: function (data) {
                console.error('An error occurred during the submission of the form.');

                $statusText.html('Error');
                $statusIcon.addClass('fa-times');
                $statusIcon.removeClass('fa-spinner fa-spin');
                $statusButton.removeClass('btn-light');
                $statusButton.addClass('btn-danger');
            },
        });
    });

});

