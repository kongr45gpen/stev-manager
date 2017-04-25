class AddJoinedToVolunteers < ActiveRecord::Migration[5.0]
  def change
    add_column :volunteers, :joined, :boolean
  end
end
