class ChangeSpaceInEvent < ActiveRecord::Migration[5.0]
  def change
    remove_column :events, :space, :text
    add_reference :events, :space

    add_column :spaces, :display, :text
  end
end
