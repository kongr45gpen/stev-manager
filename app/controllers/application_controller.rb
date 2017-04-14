class ApplicationController < ActionController::Base
  protect_from_forgery with: :exception
  before_action :set_locale

  def after_sign_in_path_for(resource_or_scope)
    root_path
  end

  def set_locale
    I18n.locale = current_user.try(:locale) || I18n.default_locale
  end
end
