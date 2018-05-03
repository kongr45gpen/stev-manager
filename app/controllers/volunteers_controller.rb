class VolunteersController < ApplicationController
  before_action :authenticate_user!

  def index
    @volunteers = Volunteer.all
  end

  def export
    @volunteers = Volunteer.all
  end

  def stats
    @volunteers = Volunteer.all
  end

  def export_pw
    volunteers = ProfessorWeek::Volunteer.all
    if true
      @separate = true
      @volunteers = {}
      volunteers.each do |volunteer|
        volunteer.available_dates.each do |datetime|
          date = datetime.to_date
          @volunteers[date] = [] if @volunteers[date].nil?
          @volunteers[date].push volunteer
        end
      end

      console

      @volunteers = @volunteers.sort.to_h.map{|k,v| [I18n.l(k,format: :sched,locale: :el), v.sort_by(&:surname).uniq]}
    else
      @separate = false
      @volunteers = { Settings.schedule.title.truncate(27) => volunteers }
    end
  end

  def stats_active
    @volunteers = Volunteer.where('joined = true or updates = true')
    render :stats
  end
end
