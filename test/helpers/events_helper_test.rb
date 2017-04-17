require 'test_helper'

class EventsHelperTest < ActiveSupport::TestCase
  include EventsHelper

  test "all repetitions having no time" do
    event = Event.new
    event.repetitions = [
        Repetition.new(date: DateTime.now.midnight, time: false),
        Repetition.new(date: DateTime.now.change({ hour: 12 }), time: false),
        Repetition.new(date: DateTime.now.change({ hour: 17 }), time: false),
        Repetition.new(date: DateTime.now.change({ hour: 5, min: 7, sec: 45 }), time: false),
    ]

    assert repetitions_same_time?(event)
    assert_nil common_time(event)
  end

  test "repetitions with different time" do
    event = Event.new
    event.repetitions = [
        Repetition.new(date: DateTime.now.midnight, time: true),
        Repetition.new(date: DateTime.now.change({ hour: 11 }), time: true)
    ]

    assert_not repetitions_same_time?(event)
  end

  test "repetitions with same time" do
    event = Event.new
    event.repetitions = [
        Repetition.new(date: DateTime.now.utc.tomorrow.change({ hour: 11 }), time: false),
        Repetition.new(date: DateTime.now.utc.change({ hour: 15 }), time: true),
        Repetition.new(date: DateTime.now.utc.tomorrow.change({ hour: 15 }), time: true),
    ]

    assert repetitions_same_time?(event)
    assert_equal("15:00", common_time(event))
  end

  test "event with no repetitions" do
    event = Event.new

    assert repetitions_same_time?(event)
    assert_nil common_time(event)
    assert_empty format_many_repetitions(repetitions)
  end

  test "single repetition formatting" do
    friday = Date.new(2017,2,3)
    monday = Date.new(2017,4,17)

    assert_equal("Παρασκευή 3/2", format_one_date(friday))
    assert_equal("Δευτέρα 17/4", format_one_date(monday))

    repetitions = [Repetition.new(date: monday)]

    assert_equal("Δευτέρα 17/4", format_many_repetitions(repetitions))
    assert_not many_repetitions? repetitions
  end

  test "repetitions with different dates" do
    repetitions = [
        Repetition.new(date: Date.new(2017,4,17)),
        Repetition.new(date: Date.new(2017,2,3)),
        Repetition.new(date: Date.new(2017,4,18)),
    ]

    assert_equal("Παρασκευή 3/2, Δευτέρα 17/4, Τρίτη 18/4", format_many_repetitions(repetitions))
    assert many_repetitions? repetitions
  end

  test "long repetitions" do
    repetitions = [
        Repetition.new(date: Date.new(2017,4,17)),
        Repetition.new(date: Date.new(2017,2,3), end_date: Date.new(2017,3,30)),
        Repetition.new(date: Date.new(2017,4,18), end_date: Date.new(2017,5,18)),
    ]

    assert_equal("Από Παρασκευή 3/2 έως Πέμπτη 30/3, Δευτέρα 17/4, Από Τρίτη 18/4 έως Πέμπτη 18/5", format_many_repetitions(repetitions))
  end

  test "two close repetitions" do
    repetitions = [
        Repetition.new(date: Date.new(2017,4,17)),
        Repetition.new(date: Date.new(2017,4,18)),
    ]

    assert_equal("Δευτέρα 17/4, Τρίτη 18/4", format_many_repetitions(repetitions))
  end

  test "two faraway repetitions" do
    repetitions = [
        Repetition.new(date: Date.new(2017,4,17)),
        Repetition.new(date: Date.new(2017,4,19)),
    ]

    assert_equal("Δευτέρα 17/4 και Τετάρτη 19/4", format_many_repetitions(repetitions))
  end

  test "repetitions with time" do
    repetitions = [
        Repetition.new(date: DateTime.new(2017,4,17,9,30), time: true),
        Repetition.new(date: DateTime.new(2017,4,19,21,15), time: true),
        Repetition.new(date: DateTime.new(2017,4,19,15,00), time: true),
        Repetition.new(date: DateTime.new(2017,4,20,23,15)),
    ]

    assert_equal("Δευτέρα 17/4 09:30, Τετάρτη 19/4 15:00, Τετάρτη 19/4 21:15, Πέμπτη 20/4", format_many_repetitions(repetitions, time: true))
    assert_equal("Δευτέρα 17/4, Τετάρτη 19/4, Τετάρτη 19/4, Πέμπτη 20/4", format_many_repetitions(repetitions, time: false))
  end

  test "repetition with duration" do
    repetition = Repetition.new(date: DateTime.new(2017,4,17,20, 0), time: true, duration: 3.25)

    assert_equal("από 20:00 έως 23:15", format_time(repetition))
    assert_equal("από 20:00 έως 23:15", common_time(Event.new(repetitions: [repetition])))
  end
end