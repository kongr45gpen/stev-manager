require_dependency 'views/index_as_final'

ActiveAdmin.register Volunteer do
  permit_params :surname, :name, :age, :email, :phone, :property, :school, :level, :health, :interests,
                :subscription, :updates, :gender

  config.per_page = 500

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
    column I18n.t('short.updates'), :updates
    column I18n.t('short.joined'), :joined
    actions do |vol|
      item I18n.t('short.mark_joined'), "/", class: "member_link"
    end
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

  batch_action :mark_joined do |ids|
    batch_action_collection.find(ids).each do |vol|
      vol.joined = true
      vol.save
    end
    redirect_to collection_path, alert: "The volunteers have been marked as joined."
  end

  index as: ActiveAdmin::Views::IndexAsFinal do
    column("Επίθετο") {|volun| volun.surname}
    column("Όνομα") {|volun| volun.name}
    column("Σχολή") {|volun| volun.faculty}
    column("E-Mail") {|volun| volun.email}
  end
end
