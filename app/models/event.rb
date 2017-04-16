class Event < ApplicationRecord
  belongs_to :submitter
  has_many :repetitions

  accepts_nested_attributes_for :submitter, :allow_destroy => true
  accepts_nested_attributes_for :repetitions, :allow_destroy => true

  serialize :fields, Array
end
