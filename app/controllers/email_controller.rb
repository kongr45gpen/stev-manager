class EmailController < ApplicationController
  before_action :set_volunteers
  before_action :authenticate_user!

  def index
  end

  def sw_emails
  end

  def pw_emails
  end

  def sw_emails_submitters
  end

  def pw_emails_submitters
  end

  def procmails
    if params[:debug]
      BulkMailer.batch_email.deliver_now
      notice = 'Debug email sent to ' + Settings.email.debug_to
    else
      group = @volunteers[params[:group].to_i]
      BulkMailer.batch_email(group).deliver_now
      notice = 'Email sent to %s..%s (#%d)' % [group.first.email,group.last.email,params[:group]]
    end

    redirect_to email_path, notice: notice
  end

  private
  def set_volunteers
    @volunteers = Volunteer.order(:id).each_slice(12).to_a
  end

end
