class CreateSubmitters < ActiveRecord::Migration[5.0]
  def change
    create_table :submitters do |t|
      t.text :surname
      t.text :name

      t.timestamps
    end
  end
end
