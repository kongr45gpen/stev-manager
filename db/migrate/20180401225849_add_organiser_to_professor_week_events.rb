class AddOrganiserToProfessorWeekEvents < ActiveRecord::Migration[5.0]
  def change
    add_column :professor_week_events, :organiser, :text
  end
end
