class ScheduleController < ApplicationController
  before_action :authenticate_user!
  before_action :set_events
  include ScheduleHelper

  def index
  end

  def index_pw
    @events = ProfessorWeek::Event.all
  end

  def index_raw
    @hidebox = true
    render "index"
  end

  def simple
  end

  def preview
    @source = ScheduleController.new.render_to_string(
        "schedule/index.tex.erb", locals: { :@events => @events }
    )

    @formatter = Rouge::Formatters::HTML.new
    @lexer = Rouge::Lexers::TeX.new

    # Get some CSS
    @theme = Rouge::Themes::Base16.mode(:dark)
  end

  def preview_simple
    @code = PandocRuby.convert(markdown_source, :from => :markdown, :to => :html)
  end

  private
  def set_events
    @events = sort_events Event.all, false
  end
end
