class Property < ApplicationRecord
  belongs_to :event
  audited
end
