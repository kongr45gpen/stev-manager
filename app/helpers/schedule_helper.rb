module ScheduleHelper
end

class String
  def texcape
    self.gsub('&','\\&').html_safe
  end
end