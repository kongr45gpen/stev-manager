class CreateVolunteers < ActiveRecord::Migration[5.0]
  def change
    create_table :volunteers do |t|
      t.string :surname
      t.string :name
      t.integer :age
      t.string :email
      t.string :phone
      t.string :property
      t.string :school
      t.string :level
      t.text :health
      t.text :interests
      t.boolean :subscription

      t.timestamps
    end
  end
end
