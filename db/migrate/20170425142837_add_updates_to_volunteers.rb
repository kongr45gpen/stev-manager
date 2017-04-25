class AddUpdatesToVolunteers < ActiveRecord::Migration[5.0]
  def change
    add_column :volunteers, :updates, :boolean
  end
end
