class AddAvailabilityToProfessorWeekVolunteers < ActiveRecord::Migration[5.0]
  def change
    add_column :professor_week_volunteers, :available_dates, :text
  end
end
