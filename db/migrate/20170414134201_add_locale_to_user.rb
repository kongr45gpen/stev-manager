class AddLocaleToUser < ActiveRecord::Migration[5.0]
  def change
    add_column :users, :locale, :string, :limit => 16
  end
end
