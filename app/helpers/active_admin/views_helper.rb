module ActiveAdmin::ViewsHelper
  def to_color(status)
    case status
      when "cancelled"
        :red
      when "fresh"
        :green
      when "pending"
        ''
      when "scheduling"
        ''
      when "confirmed"
        :yes
      when "no_schedule"
        ''
      when "pending_schedule"
        :orange
      when "scheduled"
        :yes
      when "create"
        :green
      when "update"
        :yes
      when "delete"
        :red
      else
        :red
    end
  end
end
