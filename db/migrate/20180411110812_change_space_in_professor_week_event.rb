class ChangeSpaceInProfessorWeekEvent < ActiveRecord::Migration[5.0]
  def change
    remove_reference :professor_week_events, :space

    add_column :professor_week_events, :space, :text
  end
end
