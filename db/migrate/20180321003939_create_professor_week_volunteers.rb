class CreateProfessorWeekVolunteers < ActiveRecord::Migration[5.0]
  def change
    create_table :professor_week_volunteers do |t|
      t.string :surname
      t.string :name
      t.string :father_name
      t.integer :age
      t.string :email
      t.string :phone
      t.string :property
      t.string :school
      t.string :level
      t.text :health
      t.boolean :preparation
      t.boolean :subscription
      t.boolean :updates
      t.integer :gender
      t.boolean :joined

      t.timestamps
    end
  end
end
