class ProfessorWeek::Event < ApplicationRecord
  belongs_to :space
  has_many :repetitions, :class_name => 'ProfessorWeek::Repetition'
  has_and_belongs_to_many :submitters
  audited

  serialize :fields, Array

  accepts_nested_attributes_for :submitters,  :allow_destroy => true
  accepts_nested_attributes_for :space,       :allow_destroy => true
  accepts_nested_attributes_for :repetitions, :allow_destroy => true

  enum status: { cancelled: -1, fresh: 0, pending: 1, scheduling: 2, confirmed: 3  }
  enum scheduled: { no_schedule: 0, pending_schedule: 1, scheduled: 2 }
end
