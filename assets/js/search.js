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
    const $statusCounterButton = $("#js--search-status-count-button");

    const $replaceAllButton = $("#js--replace-all");
    const $resultsContainer = $("#js--results-container");

    let currentRequest = null;

    $(".js--search-updater").on('input', function() { // TODO: Handle change events as well
        // TODO: Do not re-search on same input?
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

        // Cancel previous request
        if (currentRequest) {
            currentRequest.abort();
        }

        // Display stuff
        $statusText.html('Loading&hellip;');
        $statusIcon.removeClass('fa-times fa-check fa-exclamation-triangle');
        $statusIcon.addClass('fa-spinner fa-spin');
        $statusButton.removeClass('btn-danger btn-warning btn-success');
        $statusButton.addClass('btn-light');
        $statusCounterButton.addClass('btn-light');
        $statusCounterButton.removeClass('btn-danger');
        $replaceAllButton.prop('disabled', true);

        currentRequest = $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),
            success: function (data) {
                $("#js--results-container").html(data);

                // Display stuff (reset)
                $statusIcon.removeClass('fa-spinner fa-spin');
                $statusButton.removeClass('btn-light');
                const count = $("#js--results").attr('data-count');
                $statusCounter.text(count);
                if (count != 0) {
                    $replaceAllButton.prop('disabled', false);
                }
                if (count == 0 && $findInput.val().length !== 0) {
                    $statusButton.addClass('btn-warning');
                    $statusText.html('Not Found');
                    $statusIcon.addClass('fa-exclamation-triangle');
                } else {
                    $statusButton.addClass('btn-success');
                    $statusText.html('Ready');
                    $statusIcon.addClass('fa-check');
                }
            },
            error: function (rq) {
                if (rq.statusText === 'abort') {
                    // Not really an error
                    return;
                }

                console.error('An error occurred during the submission of the form.');

                $statusText.html('Error');
                $statusIcon.addClass('fa-times');
                $statusIcon.removeClass('fa-spinner fa-spin');
                $statusButton.removeClass('btn-light');
                $statusButton.addClass('btn-danger');
            },
        });
    });

    $resultsContainer.on('click', '.js--results-occurrence', function (e) {
        e.preventDefault();

        const name = 'replace-entity';
        const value = $(this).attr('data-target');

        $("#js--results-form").trigger('submit', {
            caller: {
                name: name,
                value: value,
                selector: $(this)
            }
        });
    });


    $resultsContainer.on('submit', '#js--results-form', function (e, params) {
        e.preventDefault();

        if (currentRequest) currentRequest.abort();

        let $pushedButton;
        const $replaceForm = $(this);
        let formData = $replaceForm.serializeArray();

        if (params !== undefined && params['caller'] !== undefined) {
            $pushedButton = params['caller']['selector'];
            formData.push({
                name: params['caller']['name'],
                value: params['caller']['value']
            });
        } else {
            $pushedButton = $replaceForm.find('button:focus'); // TODO: This is terrible
            formData.push({
                name: $pushedButton.attr('name'),
                value: $pushedButton.attr('value')
            });
        }
        const $searchResult = $("#" + $pushedButton.parents('.js--results-result').attr('id'));

        $pushedButton.html('<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Loading&hellip;');
        $pushedButton.prop('disabled', true);

        $.ajax({
            type: $replaceForm.attr('method'),
            url: $replaceForm.attr('action'),
            data: formData,
            success: function (data) {
                $pushedButton.html('<i class="fas fa-times" aria-hidden="true"></i> Unknown Error');

                const $html = $(data);
                $searchResult.html($html.find('.js--results-result').html());
            },
            error: function (rq) {
                if (rq.statusText === 'abort') {
                    // Not really an error
                    return;
                }

                console.error('An error occurred during the submission of the form.');

                $statusText.html('Error');
                $statusIcon.addClass('fa-times');
                $statusIcon.removeClass('fa-spinner fa-spin');
                $statusButton.removeClass('btn-light');
                $statusButton.addClass('btn-danger');
                $pushedButton.html('<i class="fas fa-times" aria-hidden="true"></i> Error');
            },
        });
    })
});

