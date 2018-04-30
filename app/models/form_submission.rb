class FormSubmission < ApplicationRecord
  belongs_to :user
  belongs_to :audited_audit, class_name: "Audited::Audit"
  audited

  serialize :payload
end
