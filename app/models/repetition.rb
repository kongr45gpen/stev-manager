class Repetition < ApplicationRecord
  belongs_to :event
  default_scope { order(date: :asc) }

  after_initialize :init

  def init
    self.date  ||= Date.today           #will set the default value only if it's nil
  end

  def time
    if self.time?
      self.date.strftime "%H%M%S%N"
    else
      false
    end
  end

  def end_time
    date + self.duration.hours
  end
end
