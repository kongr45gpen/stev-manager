class Submitter < ApplicationRecord
    has_many :events
    audited

    accepts_nested_attributes_for :events, :allow_destroy => true

    def full_name
        surname.to_s + ' ' + name.to_s
    end

    def male?
        name[-1] == "ς" or name[-1] == "λ" or name[-1] == "ν"
    end
end
