class CreateProperties < ActiveRecord::Migration[5.0]
  def change
    create_table :properties do |t|
      t.string :name
      t.text :value
      t.references :event, foreign_key: true

      t.timestamps
    end
  end
end
