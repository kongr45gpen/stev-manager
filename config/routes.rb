Rails.application.routes.draw do
  devise_for :users, ActiveAdmin::Devise.config

  get 'landing/index'

  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
  
  root 'landing#index'

  ActiveAdmin.routes(self)
  
  #resources :submitters
  resources :events

  match '/set_locale', to: 'landing#user_set_locale', via: :post

  get '/schedule', to: 'schedule#index'
  get '/schedule/preview'

  get '/import', to: 'import#index'
  match '/import/process', to: 'import#upload', via: :post
end
