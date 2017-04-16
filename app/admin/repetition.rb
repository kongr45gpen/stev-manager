ActiveAdmin.register Repetition do
  belongs_to :event, optional: true

  permit_params :date, :time, :end_date, :duration, :event
end