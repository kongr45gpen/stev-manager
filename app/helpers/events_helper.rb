module EventsHelper
  def repetitions_same_time?(event)
    common_time = nil

    event.repetitions.select {|x| x.time?}.each do |repetition|
      if common_time.nil?
        common_time = repetition.time
      elsif common_time != repetition.time
        return false
      end
    end

    true
  end

  def common_time(event)
    repetitions = event.repetitions.select { |x| x.time? }

    if repetitions.any?
      format_time repetitions.first
    else
      nil
    end
  end

  def format_time(rep)
    formatted = I18n.l rep.date.to_time, format: :sched
    if rep.duration
      "από %s έως %s" % [formatted, I18n.l(rep.end_time.to_time, format: :sched)]
    else
      formatted
    end
  end

  def format_one_date(dt, month=true)
    day = I18n.l dt.to_date, format: "%A", locale: :el
    if month
      "%s %d/%d" % [day, dt.day, dt.month]
    else
      "%s %d" % [day, dt.day]
    end
  end

  def format_one_repetition(rep)
    if rep.end_date
      "Από %s έως %s" % [format_one_date(rep.date), format_one_date(rep.end_date)]
    else
      format_one_date rep.date
    end
  end

  def format_many_repetitions(reps, options = {})
    reps = reps.sort_by &:date

    if reps.none?
      ''
    elsif reps.one?
      format_one_repetition reps.first
    else
      separator = ', '
      if reps.count == 2
        dt1, dt2 = reps.map { |rep| rep.date.clone.utc.midnight }
        if ((dt1-dt2).to_i / 1.day).abs > 1.001
          separator = ' και '
        end
      end

      reps.map! do |rep|
        if options[:time] and rep.time?
          format_one_repetition(rep)+ ' ' + format_time(rep)
        else
          format_one_repetition(rep)
        end
      end
      reps.join(separator)
    end
  end

  def many_repetitions?(reps)
    if reps.many?
      true
    elsif reps.one? and reps.first.end_date
      true
    else
      false
    end
  end

  def sort_events(events)
    events.sort_by do |evt|
      if evt.repetitions.any?
        evt.first_repetition.date else 0
      end
    end
  end

  def consecutive_concerts(event)
    def remove_away(lst)
      last = nil
      last_idx = nil

      lst.each_with_index do |evt,idx|
        if last and evt.first_repetition.date - last.first_repetition.date > 1.5.days
          break
        else
          last = evt
          last_idx = idx
        end
      end

      lst[0..last_idx]
    end

    events = sort_events(Event.where(kind: 'concert'))
    events.select!{ |evt| evt.repetitions.any? }
    events.select!{ |evt| event.first_repetition.date - evt.first_repetition.date < 1.5.days }
    events = remove_away(events)

    events
  end
end
