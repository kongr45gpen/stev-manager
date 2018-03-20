ActiveAdmin.register ProfessorWeek::Event do
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
  permit_params do
    permitted = [:title, :kind, :space_id, :status, :scheduled, :hidden, :ages,
                 :registration_required, :registration_email, :registration_deadline,
                 :details_costs, :details_dates, :description, :abstract,
                 :collaborator_count, :student_count, :volunteer_count
                 ]
    permitted
  end

  index do
    column :id
    selectable_column
    column :title
    column(:status) {|event| status_tag(event.status, class: to_color(event.status), label: I18n.t(event.status)) }
    column(:scheduled) {|event| status_tag(event.scheduled, class: to_color(event.scheduled), label: I18n.t(event.scheduled)) }
    column :hidden
    column :title
    column :kind
    column :repetitions do |event|
      link_to I18n.t(:times, count: event.repetitions.count), admin_event_repetitions_path(event)
    end
    actions
  end

  includes :space, :submitters, :repetitions

  sidebar :repetitions, only: [:show, :edit] do
    resource.repetitions.each do |repetition|
      attributes_table_for repetition do
        row :date
        row :duration
        row 'Actions' do
          a 'Edit', href: admin_event_repetition_path(professor_week_event, repetition)
        end
      end
    end
  end

  sidebar :submitters, only: [:show, :edit] do
    resource.submitters.each do |submitter|
      attributes_table_for submitter do
        row('Submitter') { auto_link submitter }
        row :property
        row :faculty
        row :school
        row :phone
        row :email
      end
    end
  end

  sidebar :space, only: [:show, :edit] do
    attributes_table_for professor_week_event.space do
      row(:name) { auto_link professor_week_event.space }
      row :display
      row 'Actions' do
        a 'New', href: new_admin_space_path
        a 'Edit', href: edit_admin_space_path(professor_week_event.space)
      end
    end
  end

  form do |f|
    f.actions
    f.panel 'Information' do
      columns do
        column do
          f.inputs do
            f.input :id, input_html: { disabled: true }
            f.input :title
            f.input :space
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
      f.input :fields, as: :serialized_array, collection: %w[experiment observation lab presentation collection game demonstration]
      f.input :abstract, input_html: { rows: 2 }
      f.input :description, input_html: { rows: 5 }
      f.input :ages, input_html: { rows: 2 }
    end
    f.panel 'Submission' do
      columns do
        column do
          f.inputs 'Proposal' do
            f.input :details_dates, input_html: { rows: 6 }
            f.input :details_costs, input_html: { rows: 10 }
            f.input :student_count
            f.input :collaborator_count
            f.input :volunteer_count
          end
        end
        column do
          f.has_many :submitters, new_record: true, allow_destroy: true do |t|
            t.input :name, as: :string
            t.input :surname, as: :string
            t.input :phone
            t.input :email
          end
        end
      end
    end
    f.inputs 'Repetitions' do
      f.has_many :repetitions, new_record: true, allow_destroy: true do |t|
        t.input :date, value: Date.today
        t.input :duration, label: 'Duration in minutes'
      end
    end

    f.inputs 'Registration' do
      f.input :registration_required
      f.input :registration_email
      f.input :registration_max, hint: "Max number of participants PER RUN"
      f.input :registration_deadline
    end

    f.actions
  end
end
