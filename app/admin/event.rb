require_dependency 'inputs/serialized_array_input'
require_dependency 'views/index_as_media'

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
                 :submitter_id, :status, :scheduled, :hidden, :place_description, :team_below, :space_id,
                 :time_description,
                 :submitter_attributes => [:id, :surname, :name, :phone, :email],
                 :repetitions_attributes => [:id, :date, :time, :end_date, :duration, :space_override_id, :_destroy],
                 :properties_attributes => [:id, :name, :value, :_destroy],
                 :space_attributes => [:id, :name, :address, :display],
                 :fields => []]
    permitted
  end

  includes :space, :submitter, :repetitions

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

  sidebar :space, only: [:show, :edit] do
    attributes_table_for event.space do
      row(:name) { auto_link event.space }
      row :address
      row :capacity
      row :display
      row "Actions" do
        a "New", href: new_admin_space_path
        a "Edit", href: edit_admin_space_path(event.space)
      end
    end
  end

  sidebar :properties, :only => [:show, :edit] do
    table_for resource.properties do |prop|
      column :name
      column :value
    end

  end

  index do
    column :id
    selectable_column
    column :team
    column(:status) {|event| status_tag(event.status, class: to_color(event.status), label: I18n.t(event.status)) }
    column(:scheduled) {|event| status_tag(event.scheduled, class: to_color(event.scheduled), label: I18n.t(event.scheduled)) }
    column :hidden
    column :title
    column :kind
    column :submitter
    column :space
    column :abstract do |event|
      I18n.t(:words, count: event.abstract.split(/[^[[:word:]]]+/).count)
    end
    column :repetitions do |event|
      link_to I18n.t(:times, count: event.repetitions.count), admin_event_repetitions_path(event)
    end
    actions
  end

  index as: ActiveAdmin::Views::IndexAsMedia do
    column("Επίθετο") {|event| event.submitter&.surname}
    column("Όνομα") {|event| event.submitter&.name}
    column("Τηλέφωνο") {|event| event.submitter&.phone}
    column(:email) {|event| event.submitter&.email}
    column :team
    column("Ημερομηνίες") {|event| format_many_repetitions(event.repetitions, time: true)}
    column :title
    column :space
  end

  scope :all, default: true
  scope('Complete') { |scope| scope.where(status: 'confirmed', scheduled: 'scheduled') }
  scope('Pending') { |scope| scope.where.not(['(status = ? and scheduled = ?) or (status = ? and scheduled = ?) or (status = ?)',
                                              Event.statuses[:fresh],
                                              Event.scheduleds[:no_schedule],
                                              Event.statuses[:confirmed],
                                              Event.scheduleds[:scheduled],
                                              Event.statuses[:cancelled]
                                             ]) }
  scope('Cancelled') { |scope| scope.where(status: 'cancelled') }
  scope('New') { |scope| scope.where(status: 'fresh', scheduled: 'no_schedule') }

  form do |f|
    f.actions
    f.panel 'Information' do
    columns do
      column do
        f.inputs do
          f.input :id, :input_html => { :disabled => true }
          f.input :title, as: :text, :input_html => { :rows => 1 }
          f.input :team, as: :text, :input_html => { :rows => 1 }, hint: I18n.t(:useful_symbols) + ": - – — “ ” « »"
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
      f.input :abstract, :input_html => { :rows => 5  }, hint: "<a href='https://pandoc.org/MANUAL.html#pandocs-markdown'>Pandoc Markdown</a> formatting".html_safe
      f.input :space, input_html: { class: "select2" }, hint: ("Create a <a href='" + new_admin_space_path + "' target='_blank'>new space</a>").html_safe
      # f.a "New", href: new_admin_space_path
    end
    f.panel 'Submission' do
      columns do
        column do
          f.inputs 'Proposal' do
            f.input :other, :input_html => { :rows => 4  }
            f.input :proposed_space, :as => :string
            f.input :proposed_time, :as => :string
            end
        end
        column do
          f.has_many :submitter, new_record: true, allow_destroy: false do |t|
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
        t.input :date, value: Date.today
        t.input :time, :as => :boolean, label: I18n.t(:show_time), :input_html => { checked: t.object.time? }
        t.input :end_date
        t.input :duration, hint: I18n.t(:duration_hours)
        t.input :space_override, input_html: { class: "select2" }
      end
    end
    f.panel 'Display Details' do
      columns do
        column do
          f.inputs 'Defaults' do
            f.input :place_description, input_html: { placeholder: "Χώρος διεξαγωγής" }
            f.input :time_description, input_html: { placeholder: "Ώρα" }
            f.input :team_below
          end
        end
        column do
          f.has_many :properties, new_record: true, allow_destroy: true do |t|
            t.input :name
            t.input :value, :input_html => { :rows => 1 }
            t.input :position
          end
        end
      end
    end
    f.actions
  end
end
