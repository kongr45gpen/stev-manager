class EventsController < ApplicationController
  before_action :set_event, only: [:show, :edit, :update, :destroy]
  before_action :authenticate_user!
  include EventsHelper

  # GET /events
  # GET /events.json
  # GET /events.tex
  def index
    @events = sort_events Event.all
  end

  def stats
    @events = Event.where.not(status: 'cancelled')
                  .group_by{|ev| [ev.title, ev.team] }
                  .map{|g| g.second.first}
  end

  def index_pw
    @events = ProfessorWeek::Event.all
  end

  # GET /events/1
  # GET /events/1.json
  def show;
  end

  def export
    @events = sort_events Event.all, false
    @formal = params[:final]
  end

  def export_pw   
    events = ProfessorWeek::Event.all
    if params[:separate]
        @separate = true
        @events = {}
        events.each do |event|
            event.date_dates_dates.each do |date|
                @events[date] = [] if @events[date].nil?
                @events[date].push event
            end
        end

        @events = @events.sort.to_h.map{|k,v| [I18n.l(k,format: :sched,locale: :el), v]}
    else
        @separate = false
        @events = { Settings.schedule.title.truncate(27) => events }
    end
  end

  def places_pw
    @events = ProfessorWeek::Event.all
  end

  private

  # Use callbacks to share common setup or constraints between actions.
  def set_event
    @event = Event.find(params[:id])
  end

  # Never trust parameters from the scary internet, only allow the white list through.
  def event_params
    params.require(:event).permit(:team, :title, :kind, :other, :proposed_space, :proposed_time, :submitter_id)
  end
end
