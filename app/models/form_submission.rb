class FormSubmission < ApplicationRecord
  belongs_to :user
  has_one :audit
end
