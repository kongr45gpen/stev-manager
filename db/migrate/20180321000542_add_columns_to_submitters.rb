class AddColumnsToSubmitters < ActiveRecord::Migration[5.0]
  def change
    add_column :submitters, :sector, :string
    add_column :submitters, :lab, :string
    add_column :submitters, :phone_other, :string
  end
end
