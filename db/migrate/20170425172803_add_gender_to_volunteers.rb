class AddGenderToVolunteers < ActiveRecord::Migration[5.0]
  def change
    add_column :volunteers, :gender, :integer
  end
end
