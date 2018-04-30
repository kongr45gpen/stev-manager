class RenameProfessorWeekVolunteerAudit < ActiveRecord::Migration[5.0]
  def change
    rename_column :form_submissions, :audit_id, :audited_audit_id
  end
end
