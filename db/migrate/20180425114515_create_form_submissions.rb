class CreateFormSubmissions < ActiveRecord::Migration[5.0]
  def change
    create_table :form_submissions do |t|
      t.references :user, foreign_key: true
      t.string :form
      t.text :payload
      t.references :audit
      t.string :ip_address

      t.timestamps
    end
  end
end
