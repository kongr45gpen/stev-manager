class AddSeparateToRepetitions < ActiveRecord::Migration[5.0]
  def change
    add_column :repetitions, :separate, :boolean
  end
end
