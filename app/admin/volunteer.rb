ActiveAdmin.register Volunteer do
  permit_params :surname, :name, :age, :email, :phone, :property, :school, :level, :health, :description,
                :subscription, :updates, :gender

  index do
    selectable_column
    id_column
    column :name
    column :surname
    column :age
    column :email
    column :phone
    column :property
    column :school
    column :level
    column :updates
    actions
  end

  batch_action :set_gender, form: {
      gender: %w[other male female],
  } do |ids, inputs|
    batch_action_collection.find(ids).each do |vol|
      vol.gender = inputs['gender']
      vol.save
    end
    redirect_to collection_path, alert: "The volunteers' gender has been set."
  end
end
