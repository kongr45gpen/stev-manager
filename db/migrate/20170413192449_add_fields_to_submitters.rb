class AddFieldsToSubmitters < ActiveRecord::Migration[5.0]
  def change
    add_column :submitters, :property, :string
    add_column :submitters, :faculty, :string
    add_column :submitters, :school, :string
    add_column :submitters, :phone, :string
    add_column :submitters, :email, :string
  end
end
