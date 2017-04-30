class AddTimeDescriptionToEvents < ActiveRecord::Migration[5.0]
  def change
    add_column :events, :time_description, :string
  end
end
