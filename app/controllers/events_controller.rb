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
    @events = Event.where(status: 'confirmed', scheduled: 'scheduled')
                  .group_by{|ev| [ev.title, ev.team] }
                  .map{|g| g.second.first}
  end

  # GET /events/1
  # GET /events/1.json
  def show
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
