class ProfessorWeek::Event < ApplicationRecord
  has_many :repetitions, :class_name => 'ProfessorWeek::Repetition'
  has_and_belongs_to_many :submitters
  audited

  serialize :fields, Array
  serialize :date_dates, Array

  accepts_nested_attributes_for :submitters,  :allow_destroy => true
  accepts_nested_attributes_for :repetitions, :allow_destroy => true

  enum status: { cancelled: -1, fresh: 0, pending: 1, scheduling: 2, confirmed: 3  }
  enum scheduled: { no_schedule: 0, pending_schedule: 1, scheduled: 2 }

  def date_start=(value)
    if value.include? 'ΜΜ' and value.to_time.hour != 12
      value = value.to_time.to_datetime.change(offset: "+0000").to_time + 12.hours
    end

    super(value)
  end

  def date_dates_dates
    self[:date_dates].map{|d| d.to_date}
  end

  def date_dates
    self[:date_dates].map{|d| d.to_date.to_s}
  end
end
