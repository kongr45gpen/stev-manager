class AddPositionToProperties < ActiveRecord::Migration[5.0]
  def change
    add_column :properties, :position, :integer
  end
end
