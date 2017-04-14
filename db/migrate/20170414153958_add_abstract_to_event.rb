class AddAbstractToEvent < ActiveRecord::Migration[5.0]
  def change
    add_column :events, :abstract, :text
  end
end
