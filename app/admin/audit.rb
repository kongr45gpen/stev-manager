ActiveAdmin.register Audited::Audit do
  actions :index, :show

  index do
    selectable_column
    column :id
    column :auditable
    column :auditable_type
    column :associated
    column :user
    column(:action) {|audit|
      status_tag(audit.action, to_color(audit.action)) }
    column :version
    column :comment
    column :remote_address
    column :created_at
    actions
  end
end