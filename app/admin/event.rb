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
    permitted = [:team, :title, :kind, :other, :proposed_space, :proposed_time, :abstract,
                 :submitter_id, :status, :scheduled, :hidden, :place_description, :team_below, :space,
                 :submitter_attributes => [:id, :surname, :name, :phone, :email],
                 :repetitions_attributes => [:id, :date, :time, :end_date, :duration, :_destroy],
                 :fields => []]
    permitted
  end

  includes :submitter, :repetitions

  sidebar :repetitions, only: [:show, :edit] do
    resource.repetitions.each do |repetition|
      attributes_table_for repetition do
        row :date
        row :time
        row :end_date
        row :duration
        row "Actions" do
          a "Edit", href: admin_event_repetition_path(event, repetition)
        end
      end
    end
  end

  sidebar :submitter_information, :only => [:show, :edit] do
    attributes_table_for event.submitter do
      row("Link") { auto_link event.submitter }
      row :surname
      row :name
      row :property
      row :faculty
      row :school
      row :phone
      row :email
    end
  end

  index do
    column :id
    selectable_column
    column :team
    column(:status) {|event| status_tag(event.status, to_color(event.status), label: I18n.t(event.status)) }
    column(:scheduled) {|event| status_tag(event.scheduled, to_color(event.scheduled), label: I18n.t(event.scheduled)) }
    column :hidden
    column :title
    column :kind
    column :submitter
    column :repetitions do |event|
      link_to I18n.t(:times, count: event.repetitions.count), admin_event_repetitions_path(event)
    end
    actions
  end

  form do |f|
    f.actions
    f.panel 'Information' do
    columns do
      column do
        f.inputs do
          f.input :id, :input_html => { :disabled => true }
          f.input :title
          f.input :team
        end
      end
      column do
        f.inputs 'Status' do
          f.input :hidden
          f.input :status
          f.input :scheduled
        end
      end
    end
    end

    f.inputs 'Details' do
      f.input :kind, as: :select, collection: ["theatre","music","photography","sports","other","concert"]
      f.input :fields,:as => :serialized_array, collection: ["theatre","music","photography","sports","other"]
      f.input :abstract, :input_html => { :rows => 5  }
      f.input :space, :input_html => { :rows => 1 }
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
          f.has_many :submitter, heading: false, new_record: true, allow_destroy: false do |t|
            t.input :name, :as => :string
            t.input :surname, :as => :string
            t.input :phone
            t.input :email
          end
        end
      end
    end
    f.inputs 'Repetitions' do
      f.has_many :repetitions, new_record: true, allow_destroy: true do |t|
        # byebug
        t.input :date, value: Date.today
        t.input :time, :as => :boolean, label: I18n.t(:show_time), :input_html => { checked: t.object.time? }
        t.input :end_date
        t.input :duration, label: 'Duration in minutes'
      end
    end
    f.inputs 'Display Details' do
      f.input :place_description, input_html: { placeholder: "Χώρος διεξαγωγής" }
      f.input :team_below
    end
    f.actions
  end
end
