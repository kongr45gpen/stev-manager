class AddFieldsToEvent < ActiveRecord::Migration[5.0]
  def change
    add_column :events, :fields, :string, array: true
  end
end
