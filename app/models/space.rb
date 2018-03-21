class Space < ApplicationRecord
  audited

  default_scope { order(name: :asc) }

  def display_description
    self[:display_name] || name
  end
end
