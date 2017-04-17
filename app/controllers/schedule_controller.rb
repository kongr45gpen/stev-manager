class ScheduleController < ApplicationController
  # before_action :authenticate_user!
  include ScheduleHelper

  def index
    @events = Event.all
  end

  def preview
    events = Event.all
    @source = ScheduleController.new.render_to_string(
        "schedule/index.tex.erb", locals: { :@events => events }
    )

    @formatter = Rouge::Formatters::HTML.new
    @lexer = Rouge::Lexers::TeX.new

    # Get some CSS
    @theme = Rouge::Themes::Base16.mode(:dark)

  end
end
