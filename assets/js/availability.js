require('./app.js');

const $ = require('jquery');

console.log('Loaded availability file');

$(document).ready(function() {
    // Change volunteer colours when they are selected
    let updateVolunteers = function() {
        // Find all our volunteers
        let volunteers = new Set();

        $(".js--availability-choose-time").each(function() {
            if ($(this).prop('checked')) {
                // Add the volunteers to the volunteers
                $(this).parents(".js--availability-time").find(".js--availability-volunteer").each(function () {
                    volunteers.add($(this).attr('data-id'));
                });
            }
        });

        // Now colour all the volunteers accordingly
        $(".js--availability-volunteer").each(function() {
            if (volunteers.has($(this).attr('data-id'))) {
                $(this).addClass('badge-primary').removeClass('badge-secondary');
            } else {
                $(this).removeClass('badge-primary').addClass('badge-secondary');
            }
        });
    };

    $(".js--availability-choose-day").on('input', function() {
        const parentChecked = $(this).prop('checked');

        $(this).parents(".js--availability-day").find(".js--availability-choose-time").each(function() {
            $(this).prop('checked', parentChecked);
        });

        updateVolunteers();
    });

    $(".js--availability-choose-time").on('input', function() {
        updateVolunteers();
    });

    // Maybe the boxes are checked for some reason
    updateVolunteers();
});
