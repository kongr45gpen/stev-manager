ActiveAdmin.register ProfessorWeek::Repetition do
  belongs_to 'ProfessorWeek::Event', optional: true

  permit_params :date, :duration, :professor_week_event
end