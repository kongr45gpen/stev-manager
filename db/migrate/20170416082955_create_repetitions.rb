class CreateRepetitions < ActiveRecord::Migration[5.0]
  def change
    create_table :repetitions do |t|
      t.datetime :date
      t.boolean :time
      t.datetime :end_date
      t.decimal :duration
      t.references :event, foreign_key: true

      t.timestamps
    end
  end
end
