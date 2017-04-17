class Event < ApplicationRecord
  belongs_to :submitter
  has_many :repetitions

  accepts_nested_attributes_for :submitter, :allow_destroy => true
  accepts_nested_attributes_for :repetitions, :allow_destroy => true

  serialize :fields, Array

  enum status: { cancelled: -1, fresh: 0, pending: 1, scheduling: 2, confirmed: 3  }
  enum scheduled: { no_schedule: 0, pending_schedule: 1, scheduled: 2 }

  def place_description
    (@place_description.nil? || @place_description.empty?) ? nil : @place_description
  end
end
