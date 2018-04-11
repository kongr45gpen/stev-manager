class AddAttributesToProfessorWeek < ActiveRecord::Migration[5.0]
  def change
    add_column :submitters, :hidden, :boolean
    add_column :professor_week_events, :position, :integer
  end
end
