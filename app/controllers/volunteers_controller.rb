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

  def stats_active
    @volunteers = Volunteer.where('joined = true or updates = true')
    render :stats
  end
end
