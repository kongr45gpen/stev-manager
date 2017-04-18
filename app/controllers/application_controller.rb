class ApplicationController < ActionController::Base
  protect_from_forgery with: :exception
  before_action :set_locale

  def after_sign_in_path_for(resource_or_scope)
    events_path
  end

  def set_locale
    I18n.locale = current_user.try(:locale) || I18n.default_locale
  end
end

class ActiveAdmin::Devise::SessionsController
  def after_sign_in_path_for(resource)
    stored_location_for(resource) || events_path
  end

  def after_sign_out_path_for(resource)
    '/'
  end
end