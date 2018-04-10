module EventsHelper
  def event_attribute_stats(attribute)
    @events.group_by(&attribute).sort_by{|key,entries| entries.count}.reverse
  end

  def repetitions_same_time?(event, options = {})
    common_time = nil
    repetitions = options[:active] ? event.active_repetitions : event.repetitions

    event.repetitions.select {|x| x.time?}.each do |repetition|
      if common_time.nil?
        common_time = repetition.time
      elsif common_time != repetition.time
        return false
      end
    end

    true
  end

  def common_time(event, options = {})
    repetitions = (options[:active] ? event.active_repetitions : event.repetitions).select { |x| x.time? }

    if repetitions.any?
      format_time repetitions.first
    else
      nil
    end
  end

  def format_time(rep)
    return unless rep
    formatted = I18n.l rep.date.to_time, format: :sched
    if rep.duration
      "Από %s έως %s" % [formatted, I18n.l(rep.end_time.to_time, format: :sched)]
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

  def format_one_repetition(rep, options = {})
    string = (options[:capitalise] or true) ? 'Από %s έως %s' : 'από %s έως %s'
    if rep.end_date
      string %= [format_one_date(rep.date), format_one_date(rep.end_date)]
    else
      string = format_one_date rep.date
    end

    if rep.space_override and not options[:hide_space]
      string += ", " + rep.space_override.display_description
    end

    if options[:time] and rep.time?
      string += ' ' + format_time(rep)
    end

    # TODO: More general rule for "εργάσιμες μέρες"
    unless rep.extra.blank? or (rep.extra == "εργάσιμες ημέρες" and not options[:time])
      if rep.extra.split(' ').size > 3
        string += '  \n'
      else
        string += ' '
      end
      string += "(%s)" % rep.extra
    end

    string
  end

  def format_many_repetitions(reps, options = {})
    reps = reps.sort_by &:date

    if reps.none?
      ''
    elsif reps.one?
      format_one_repetition reps.first, time: options[:time], hide_space: true
    else
      separator = ', '
      if reps.count == 2
        dt1, dt2 = reps.map { |rep| rep.date.clone.utc.midnight }
        if ((dt1-dt2).to_i / 1.day).abs > 1.001
          separator = ' και '
        end
      end

      first = true
      reps.map! do |rep|
        cap = (first or separator == ', ')
        first = false if first

        format_one_repetition rep, capitalise: cap, time: options[:time]
      end

      reps.join(separator)
    end
  end

  def format_event_repetitions(event, options = {})
    format_many_repetitions(options[:active] ? event.active_repetitions : event.repetitions, time: true)
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

  def sort_events(events, simple = true)
    # First, create a list of events based on separate repetitions
    unless simple
      multiple_events = []
      events.each do |evt|
        repetitions_list = []
        evt.repetitions.sort_by(&:date).each_with_index do |rep,idx|
          if rep.separate?
            new_event = evt.clone
            new_event.active_repetitions = repetitions_list
            multiple_events.push(new_event)
            # console
            repetitions_list = []
          end

          repetitions_list.push rep

          if idx == evt.repetitions.size - 1
            new_event = evt.clone
            new_event.active_repetitions = repetitions_list
            multiple_events.push(new_event)
            # console
            repetitions_list = []
          end
        end
      end
    else
      multiple_events = events
    end

    multiple_events.sort_by { |evt| evt.first_date }
  end

  def consecutive_concerts(event)
    def remove_away(lst)
      last = nil
      last_idx = nil

      lst.each_with_index do |evt,idx|
        if last and evt.first_date - last.first_date > 1.5.days
          break
        else
          last = evt
          last_idx = idx
        end
      end

      lst[0..last_idx]
    end

    if event.repetitions.empty?
      return [event]
    end

    events = sort_events(Event.where(kind: 'concert'))
    events.select!{ |evt| evt.repetitions.any? }
    events.select!{ |evt| event.first_repetition.date - evt.first_repetition.date < 1.5.days }
    events = remove_away(events)

    events
  end

  def all_properties(event)
    props = {}

    same_time = repetitions_same_time? event

    if event.repetitions.any?
      props[many_repetitions?(event.repetitions) ? "Ημερομηνίες" : "Ημερομηνία"] =
          format_many_repetitions event.repetitions, time: !same_time
    end

    if same_time and (time = common_time(event))
      props[event.time_description || "Ώρα"] = time.mb_chars.capitalize.to_s
    end

    unless event.space.empty?
      props[event.place_description || "Χώρος διεξαγωγής"] = event.space
    end

    event.properties.each do |property|
      props[property.name] = property.value
    end

    props
  end
end

class String
  # TODO: Don't duplicate code from the Volunteer helper
  def only_upper_case
    str = self.split.map{ |v| v.first + v[1,2]&.downcase }.join
    if str.length <= 6 then str else self.split.map(&:first).join end
  end

  def to_colour
    '#' + Digest::MD5.hexdigest(self)[0..5]
  end
end
