Rails.application.routes.draw do
  devise_for :users, ActiveAdmin::Devise.config

  get 'landing/index'

  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
  
  root 'landing#index'

  ActiveAdmin.routes(self)
  
  resources :events, only: [:index]
  get '/events/stats'
  get '/events/pw', to: 'events#index_pw'
  get '/events/export'
  get '/events/pw/places', to: 'events#places_pw'
  get '/events/pw/export', to: 'events#export_pw'

  get '/volunteers', to: 'volunteers#index'
  get '/volunteers/export'
  get '/volunteers/stats'
  get '/volunteers/stats_active'

  get '/email', to: 'email#index'
  match '/email', to: 'email#procmails', via: :post
  get '/email/sw/volunteers', to: 'email#sw_emails'
  get '/email/pw/volunteers', to: 'email#pw_emails'
  get '/email/sw/submitters', to: 'email#sw_emails_submitters'
  get '/email/pw/submitters', to: 'email#pw_emails_submitters'

  match '/set_locale', to: 'landing#user_set_locale', via: :post

  get '/schedule', to: 'schedule#index'
  get '/schedule/raw', to: 'schedule#index_raw'
  get '/schedule/preview'
  get '/schedule/simple'
  get '/schedule/preview_simple'
  get '/schedule/pw/', to: 'schedule#index_pw'

  get 'surveys', to: 'surveys#index'
  get 'surveys/dates'
  post 'surveys/dates', to: 'surveys#dates_process'
  get 'surveys/thanks'

  get '/import', to: 'import#index'
  match '/import/process', to: 'import#upload', via: :post
  match '/import/process/volunteers', to: 'import#upload_volunteers', via: :post
  match '/import/process/pw/events', to: 'import#upload_pw_events', via: :post
  match '/import/process/pw/volunteers', to: 'import#upload_pw_volunteers', via: :post
  match '/import/process/pw/repetitions', to: 'import#process_pw_repetitions', via: :post
  match '/import/process/pw/positions', to: 'import#process_pw_positions', via: :post
  match '/import/reset/pw/positions', to: 'import#reset_pw_positions', via: :post
end
