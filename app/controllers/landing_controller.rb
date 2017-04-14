class LandingController < ApplicationController
  def index
  end

  def user_set_locale
    current_user.update(locale: params[:locale])
    redirect_to root_path
  end
end
