class Space < ApplicationRecord
  audited

  default_scope { order(name: :asc) }

  def display_description
    self[:display_name] || name
  end

  def to_s
    display_description
  end
end
