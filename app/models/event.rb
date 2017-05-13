class Event < ApplicationRecord
  belongs_to :submitter
  has_many :repetitions
  has_many :properties, -> { order(position: :asc) }
  audited

  accepts_nested_attributes_for :submitter,   :allow_destroy => true
  accepts_nested_attributes_for :repetitions, :allow_destroy => true
  accepts_nested_attributes_for :properties,  :allow_destroy => true

  serialize :fields, Array

  enum status: { cancelled: -1, fresh: 0, pending: 1, scheduling: 2, confirmed: 3  }
  enum scheduled: { no_schedule: 0, pending_schedule: 1, scheduled: 2 }

  def place_description
    v = self[:place_description]
    (v.nil? || v.empty?) ? nil : v
  end

  def time_description
    v = self[:time_description]
    (v.nil? || v.empty?) ? nil : v
  end

  def first_repetition
    self.repetitions.first
  end

  def first_date
    if self.repetitions.any?
      self.repetitions.first.date
    else
      Date.new(0)
    end
  end

  def sanitized_space
    space.strip.gsub('.','').force_encoding('utf-8').mb_chars.titlecase.to_s
  end
end
