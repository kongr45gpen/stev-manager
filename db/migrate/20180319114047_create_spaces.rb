class CreateSpaces < ActiveRecord::Migration[5.0]
  def change
    create_table :spaces do |t|
      t.string :name
      t.string :address
      t.integer :capacity
      t.text :technical_details
      t.text :logistic_details
      t.string :contact_name
      t.string :contact_email
      t.string :contact_phone
      t.text :contact_information

      t.timestamps
    end
  end
end
