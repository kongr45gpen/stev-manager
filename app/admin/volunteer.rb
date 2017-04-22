ActiveAdmin.register Volunteer do
  permit_params :surname, :name, :age, :email, :phone, :property, :school, :level, :health, :description,
                :subscription

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
    actions
  end
end
