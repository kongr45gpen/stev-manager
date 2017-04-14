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

  permit_params :team, :title, :kind, :other, :proposed_space, :proposed_time, :abstract, :submitter_id

  form do |f|
    f.inputs 'Information' do
      f.input :title
      f.input :team
      # f.input :published_at, label: 'Publish Post At'
    end
    # f.inputs 'Content', :body
    # f.inputs do
    #   f.has_many :categories, heading: 'Themes', allow_destroy: true, new_record: false do |a|
    #     a.input :title
    #   end
    # end
    f.inputs 'Details' do
      f.input :kind, as: :select, collection: ["theatre","music","photography","sports","other"]
      f.input :other, :input_html => { :rows => 2  }
      f.input :abstract, :input_html => { :rows => 5  }
      f.input :proposed_space, :as => :string
      f.input :proposed_time, :as => :string
    end
    f.inputs 'Submitter' do
      f.has_many :submitter, new_record: false, allow_destroy: false do |t|
        t.input :name, :as => :string
        t.input :surname, :as => :string
        t.input :phone
        t.input :email
      end
    end
    # f.inputs do
    #   f.has_many :comment, new_record: 'Leave Comment',
    #              allow_destroy: proc { |comment| comment.author?(current_admin_user) } do |b|
    #     b.input :body
    #   end
    # end
    f.actions
  end
end
