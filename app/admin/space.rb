ActiveAdmin.register Space do
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

  permit_params :name, :address, :phone, :capacity, :technical_details,
                :logistic_details, :contact_name, :contact_email, :contact_information,
                :display

  index do
    column :id
    selectable_column
    column :name
    column :address
    column :capacity
    column :contact_name
    column :contact_phone
    column :contact_email
    column :display
    actions
  end

  form do |f|
    f.actions
    f.panel I18n.t(:information) do
      columns do
        column do
          f.inputs do
            f.input :id, :input_html => {:disabled => true}
            f.input :name
            f.input :address
            f.input :capacity
          end
        end
        column do
          f.inputs I18n.t(:contact_information) do
            f.input :contact_name
            f.input :contact_email
            f.input :contact_phone
            f.input :contact_information, :input_html => {:rows => 4}
          end
        end
      end
    end
    f.panel I18n.t(:details) do
      columns do
        column do
          f.inputs do
            f.input :display, :input_html => {:rows => 1}
            f.input :logistic_details
            f.input :technical_details
          end
        end
      end
    end
  end
end
