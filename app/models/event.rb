class Event < ApplicationRecord
  belongs_to :submitter

  accepts_nested_attributes_for :submitter, :allow_destroy => true

  serialize :fields, Array
end
