module ScheduleHelper
  include EventsHelper
end

class String
  def texcape
    self.gsub('&','\\\\&').gsub('_','\\\\_').html_safe
  end
end

class NilClass
  def empty?
    true
  end
end