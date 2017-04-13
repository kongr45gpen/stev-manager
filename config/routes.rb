Rails.application.routes.draw do
  devise_for :admin_users, ActiveAdmin::Devise.config
  devise_for :users
  ActiveAdmin.routes(self)
  get 'landing/index'

  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
  
  root 'landing#index'
  
  resources :submitters
  resources :events
end
