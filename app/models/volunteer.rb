class Volunteer < ApplicationRecord
  def full_name
    surname.to_s + ' ' + name.to_s
  end
end
