class Submitter < ApplicationRecord
    has_many :events

    accepts_nested_attributes_for :events, :allow_destroy => true
end
