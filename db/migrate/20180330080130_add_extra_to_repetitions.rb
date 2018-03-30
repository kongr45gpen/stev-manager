class AddExtraToRepetitions < ActiveRecord::Migration[5.0]
  def change
    add_column :repetitions, :extra, :text
  end
end
