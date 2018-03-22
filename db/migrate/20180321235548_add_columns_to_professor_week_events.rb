class AddColumnsToProfessorWeekEvents < ActiveRecord::Migration[5.0]
  def change
    add_column :professor_week_events, :date_repetition_count, :integer
    add_column :professor_week_events, :date_repetition_other, :text
    add_column :professor_week_events, :date_duration, :integer
    add_column :professor_week_events, :date_start, :time
    add_column :professor_week_events, :date_duration_total, :integer
    add_column :professor_week_events, :date_dates, :string, array: true
  end
end
