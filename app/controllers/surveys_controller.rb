require 'date_time_with_duration'

class SurveysController < ApplicationController
  include SurveysHelper
  before_action :setup

  def index
  end

  def thanks
  end

  def dates
  end

  def dates_process
    if request.parameters[:full_name].empty?
      @errors.push "Δεν έχετε εισάγει Ονοματεπώνυμο."
    end

    volunteers = locate_volunteer_by_name request.parameters[:full_name]
    dates_available = []
    preparation = request.parameters[:preparation] || false
    not_available = request.parameters[:not_available] || false

    Settings.professor_week_event_dates.each do |date|
      Settings.professor_week_student_times.each do |time|
        if request.parameters['availability-' + date + '-' + time]
          dates_available.push DateTimeWithDuration.parse_with_duration(date + ' ' + time)
        end
      end
    end

    if dates_available.empty? and not not_available
      @errors.push "Δεν έχετε επιλέξει καμία Κυριακή."
    end

    if @errors.any?
      render :dates
      return
    end

    payload = {
        name: request.parameters[:full_name],
        volunteers: volunteers,
        dates_available: dates_available,
        preparation: preparation
    }

    audit = nil
    if volunteers.one?
      # Only one volunteer. We have found a match!
      volunteers.first.update_attributes!(available_dates: dates_available, audit_comment: "Form Submission: dates")
      volunteers.first.save
      audit = volunteers.first.audits.last
    end

    @submission = FormSubmission.new(
                                   user: current_user,
                                   form: :dates,
                                   payload: payload,
                                   audit_id: audit&.id,
                                   ip_address: request.remote_ip
    )
    @submission.save

    render :thanks
  end

  def setup
    @errors = []
  end
end
