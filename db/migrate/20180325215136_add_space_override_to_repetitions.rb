class AddSpaceOverrideToRepetitions < ActiveRecord::Migration[5.0]
  def change
    add_reference :repetitions, :space_override, foreign_key: {to_table: :spaces}
  end
end
