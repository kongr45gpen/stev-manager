class Repetition < ApplicationRecord
  belongs_to :event
  default_scope { order(date: :asc) }
  audited

  after_initialize :init

  def init
    self.date  ||= Settings.default_date&.to_date || Date.today      #will set the default value only if it's nil
  end

  def time
    if self.time?
      self.date.strftime "%H%M%S%N"
    else
      false
    end
  end

  def end_time
    (end_date || date) + (self.duration&.hours || 0)
  end
end
