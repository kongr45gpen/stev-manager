class ProfessorWeek::Event < ApplicationRecord
  has_many :repetitions, :class_name => 'ProfessorWeek::Repetition'
  has_and_belongs_to_many :submitters
  audited

  serialize :fields, Array
  serialize :date_dates, Array

  default_scope { order(position: :asc) }

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

  def active_submitters
    submitters.reject(&:hidden?)
  end

  def repetitions_by_dates
    repetitions.group_by{|rep| rep.date.to_date}
  end

  def repetition_dates
    repetitions_by_dates.map(&:first)
  end

  def repetitions_by_times
    repetitions.group_by{|rep| rep.date.to_time}
  end

  def repetition_times
    repetitions_by_times.map(&:first)
  end


  def sanitized_organiser
    if organiser.to_s.blank?
      lab = submitters.group_by{|sub| sub.lab}.map(&:first).reject{|val| val.to_s.empty?}
      sector = submitters.group_by{|sub| sub.sector}.map(&:first).reject{|val| val.to_s.empty?}
      school = submitters.group_by{|sub| sub.school}.map(&:first).reject{|val| val.to_s.empty?}

      response = []
      unless lab.empty?
        response.push lab.map{|v| (v.include? "Εργαστήριο") ? v : "Εργαστήριο " + v}.join(', ')
      end
      unless sector.empty? or lab == sector
        response.push sector.map{|v| (v.include? "Τομέας") ? v : "Τομέας " + v}.join(', ')
      end
      unless school.empty?
        response.push school.map{|v| (v.include? "Τμήμα") ? v : "Τμήμα " + v}.join(', ')
      end

      response.join(', ')
    else
      organiser
    end
  end
end
