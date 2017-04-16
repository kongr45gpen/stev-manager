class Repetition < ApplicationRecord
  belongs_to :event

  after_initialize :init

  def init
    self.date  ||= Date.today           #will set the default value only if it's nil
  end
end
