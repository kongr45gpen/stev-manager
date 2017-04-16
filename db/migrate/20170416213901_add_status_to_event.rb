class AddStatusToEvent < ActiveRecord::Migration[5.0]
  def change
    add_column :events, :status, :integer, default: 0
    add_column :events, :scheduled, :integer, default: 0
    add_column :events, :hidden, :boolean, default: false, null: false
    add_column :events, :place_description, :string
    add_column :events, :team_below, :boolean, default: false
    add_column :events, :space, :text
  end
end
