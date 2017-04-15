require_dependency 'inputs/serialized_array_input'

ActiveAdmin.register Event do
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
#   permit_params :team, :title, :kind, :other, :proposed_space, :proposed_time, :abstract, :fields


  permit_params do
    permitted = [:team, :title, :kind, :other, :proposed_space, :proposed_time, :abstract, :submitter_attributes, :fields => []]
  end

  form do |f|
    f.inputs 'Information' do
      f.input :id, :input_html => { :disabled => true }
      f.input :title
      f.input :team
      # f.input :published_at, label: 'Publish Post At'
    end
    f.inputs 'Details' do
      f.input :kind, as: :select, collection: ["theatre","music","photography","sports","other"]
      f.input :fields,:as => :serialized_array, collection: ["theatre","music","photography","sports","other"]
      f.input :abstract, :input_html => { :rows => 5  }
    end
    f.panel 'Submission' do
      tabs do
        tab 'Proposal' do
          f.inputs do
            f.input :other, :input_html => { :rows => 2  }
            f.input :proposed_space, :as => :string
            f.input :proposed_time, :as => :string
            end
        end
        tab 'Submitter' do
          f.has_many :submitter, new_record: false, allow_destroy: false do |t|
            t.input :name, :as => :string
            t.input :surname, :as => :string
            t.input :phone
            t.input :email
          end
        end
      end
    end
    f.actions
  end
end
