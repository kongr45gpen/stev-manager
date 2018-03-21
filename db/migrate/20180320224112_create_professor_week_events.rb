class CreateProfessorWeekEvents < ActiveRecord::Migration[5.0]
  def change
    create_table :professor_week_events do |t|
      t.string :title
      t.string :fields, array: true
      t.references :space
      t.integer :status
      t.integer :scheduled
      t.boolean :hidden
      t.text :ages
      t.boolean :registration_required
      t.string :registration_email
      t.integer :registration_max
      t.datetime :registration_deadline
      t.text :details_costs
      t.string :collaborator_count
      t.string :student_count
      t.string :volunteer_count
      t.text :description
      t.text :abstract
      t.text :details_dates
      t.text :details_space
      t.text :details_extra

      t.timestamps
    end

    create_table :professor_week_events_submitters do |t|
      t.belongs_to :event, index: {:name => "pw_events_submitters_on_pw_event_id"}
      t.belongs_to :submitter, index: {:name => "pw_events_submitters_on_submitter_id"}
    end

    create_table :professor_week_repetitions do |t|
      t.datetime :date
      t.decimal :duration
      t.belongs_to :event, foreign_key: true

      t.timestamps
    end
  end
end
