class CreateEvents < ActiveRecord::Migration[5.0]
  def change
    create_table :events do |t|
      t.string :team
      t.string :title
      t.string :kind
      t.text :other
      t.text :proposed_space
      t.text :proposed_time
      t.references :submitter, foreign_key: true

      t.timestamps
    end
  end
end
