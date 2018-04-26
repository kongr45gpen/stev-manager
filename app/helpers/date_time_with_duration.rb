class DateTime

end

class DateTimeWithDuration < DateTime
  def duration
    4.hours
  end

  def self.parse_with_duration(what)
    p = self.parse(what)
    self.new(p.year, p.month, p.day, p.hour, p.minute, p.second)
  end
end