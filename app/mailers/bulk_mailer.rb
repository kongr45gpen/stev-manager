class BulkMailer < ApplicationMailer
  def batch_email(users = [])
    emails = []

    emails.append Settings.email.debug_to if Settings.email.debug_to
    emails += users.map(&:email)

    attachments['schedule.pdf'] = File.read(Rails.root.join('schedule.pdf'))

    mail(to: Settings.email.default_to, bcc: emails, subject: 'Πρόγραμμα Φοιτητικής Εβδομάδας')
  end
end
