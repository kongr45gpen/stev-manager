Rails.application.routes.draw do
  devise_for :users, ActiveAdmin::Devise.config
  ActiveAdmin.routes(self)
  get 'landing/index'

  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
  
  root 'landing#index'
  
  resources :submitters
  resources :events

  match '/set_locale', to: 'landing#user_set_locale', via: :post
end
