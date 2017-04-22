class VolunteersController < ApplicationController
  before_action :authenticate_user!

  def index
    @volunteers = Volunteer.all
  end
end
