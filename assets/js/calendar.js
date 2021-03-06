require('./app.js');

const Calendar = require('tui-calendar');
const $ = require('jquery');

console.log('Loaded calendar file');

// let calendar = new Calendar('#calendar', {
//     defaultView: 'day',
//     taskView: true,
//     template: {
//         monthGridHeader: function(model) {
//             var date = new Date(model.date);
//             var template = '<span class="tui-full-calendar-weekday-grid-date">' + date.getDate() + '</span>';
//             return template;
//         }
//     }
// });

let params = {
    view: 'day',
    date: new Date()
};

if (typeof getParams === 'function') {
    params = Object.assign(params, getParams());
}

let calendar = new Calendar(document.getElementById('calendar'), {
    defaultView: params['view'],
    taskView: true,    // can be also ['milestone', 'task']
    scheduleView: true,  // can be also ['allday', 'time']
    template: {
        milestone: function(schedule) {
            return '<span style="color:red;"><i class="fa fa-flag"></i> ' + schedule.title + '</span>';
        },
        milestoneTitle: function() {
            return 'Milestone';
        },
        task: function(schedule) {
            return '&nbsp;&nbsp;#' + schedule.title;
        },
        taskTitle: function() {
            return '';
        },
        allday: function(schedule) {
            return schedule.title + ' <i class="fa fa-refresh"></i>';
        },
        alldayTitle: function() {
            return 'All Day';
        },
        time: function(schedule) {
            return schedule.title + ' <i class="fa fa-refresh"></i>';
        }
    },
    month: {
        daynames: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        startDayOfWeek: 1,
        narrowWeekend: false
    },
    week: {
        daynames: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        startDayOfWeek: 1,
        narrowWeekend: false
    }
});

calendar.setDate(params['date']);

createSchedules(calendar);

$(document).ready(function() {
    $("#js--calendar-prev").click(function() {
        calendar.prev()
    });
    $("#js--calendar-next").click(function() {
        calendar.next()
    });
    $("#js--calendar-date").click(function() {
        calendar.setDate(params['date']);
    });
});
