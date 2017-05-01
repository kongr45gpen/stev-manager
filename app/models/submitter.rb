class Submitter < ApplicationRecord
    has_many :events
    audited

    accepts_nested_attributes_for :events, :allow_destroy => true

    def full_name
        surname.to_s + ' ' + name.to_s
    end
end
