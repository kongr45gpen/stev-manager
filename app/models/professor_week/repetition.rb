class ProfessorWeek::Repetition < ApplicationRecord
  belongs_to :event, :class_name => 'ProfessorWeek::Event'
  default_scope { order(date: :asc) }
  audited

  after_initialize :init

  def init
    self.date  ||= Settings.default_date&.to_date || Date.today      #will set the default value only if it's nil
  end
end
