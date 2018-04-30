ActiveAdmin.register FormSubmission do
# See permitted parameters documentation:
# https://github.com/activeadmin/activeadmin/blob/master/docs/2-resource-customization.md#setting-up-strong-parameters
#
# permit_params :list, :of, :attributes, :on, :model
#
# or
#
# permit_params do
#   permitted = [:permitted, :attributes]
#   permitted << :other if params[:action] == 'create' && current_user.admin?
#   permitted
# end

  permitted = [:user, :audited_audit, :ip_address]

  remove_filter :audited_audit
  remove_filter :audits

  form do |f|
    f.semantic_errors

    inputs do
      input :id, input_html: { disabled: true }
      input :user
      input :payload
      input :ip_address
    end

    f.actions
  end
end
