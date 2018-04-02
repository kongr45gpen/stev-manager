# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended that you check this file into your version control system.

ActiveRecord::Schema.define(version: 20180402095842) do

  create_table "active_admin_comments", force: :cascade do |t|
    t.string   "namespace"
    t.text     "body"
    t.string   "resource_id",   null: false
    t.string   "resource_type", null: false
    t.string   "author_type"
    t.integer  "author_id"
    t.datetime "created_at",    null: false
    t.datetime "updated_at",    null: false
    t.index ["author_type", "author_id"], name: "index_active_admin_comments_on_author_type_and_author_id"
    t.index ["namespace"], name: "index_active_admin_comments_on_namespace"
    t.index ["resource_type", "resource_id"], name: "index_active_admin_comments_on_resource_type_and_resource_id"
  end

  create_table "audits", force: :cascade do |t|
    t.integer  "auditable_id"
    t.string   "auditable_type"
    t.integer  "associated_id"
    t.string   "associated_type"
    t.integer  "user_id"
    t.string   "user_type"
    t.string   "username"
    t.string   "action"
    t.text     "audited_changes"
    t.integer  "version",         default: 0
    t.string   "comment"
    t.string   "remote_address"
    t.string   "request_uuid"
    t.datetime "created_at"
    t.index ["associated_id", "associated_type"], name: "associated_index"
    t.index ["auditable_id", "auditable_type"], name: "auditable_index"
    t.index ["created_at"], name: "index_audits_on_created_at"
    t.index ["request_uuid"], name: "index_audits_on_request_uuid"
    t.index ["user_id", "user_type"], name: "user_index"
  end

  create_table "events", force: :cascade do |t|
    t.string   "team"
    t.string   "title"
    t.string   "kind"
    t.text     "other"
    t.text     "proposed_space"
    t.text     "proposed_time"
    t.integer  "submitter_id"
    t.datetime "created_at",                        null: false
    t.datetime "updated_at",                        null: false
    t.text     "abstract"
    t.string   "fields"
    t.integer  "status",            default: 0
    t.integer  "scheduled",         default: 0
    t.boolean  "hidden",            default: false, null: false
    t.string   "place_description"
    t.boolean  "team_below",        default: false
    t.string   "time_description"
    t.integer  "space_id"
    t.index ["space_id"], name: "index_events_on_space_id"
    t.index ["submitter_id"], name: "index_events_on_submitter_id"
  end

  create_table "professor_week_events", force: :cascade do |t|
    t.string   "title"
    t.string   "fields"
    t.integer  "space_id"
    t.boolean  "hidden"
    t.text     "ages"
    t.boolean  "registration_required"
    t.string   "registration_email"
    t.integer  "registration_max"
    t.datetime "registration_deadline"
    t.text     "details_costs"
    t.string   "collaborator_count"
    t.string   "student_count"
    t.string   "volunteer_count"
    t.text     "description"
    t.text     "abstract"
    t.text     "details_dates"
    t.text     "details_space"
    t.text     "details_extra"
    t.datetime "created_at",                        null: false
    t.datetime "updated_at",                        null: false
    t.integer  "status",                default: 0
    t.integer  "scheduled",             default: 0
    t.integer  "date_repetition_count"
    t.text     "date_repetition_other"
    t.integer  "date_duration"
    t.time     "date_start"
    t.integer  "date_duration_total"
    t.string   "date_dates"
    t.index ["space_id"], name: "index_professor_week_events_on_space_id"
  end

  create_table "professor_week_events_submitters", force: :cascade do |t|
    t.integer "event_id"
    t.integer "submitter_id"
    t.index ["event_id"], name: "pw_events_submitters_on_pw_event_id"
    t.index ["submitter_id"], name: "pw_events_submitters_on_submitter_id"
  end

  create_table "professor_week_repetitions", force: :cascade do |t|
    t.datetime "date"
    t.decimal  "duration"
    t.integer  "event_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.index ["event_id"], name: "index_professor_week_repetitions_on_event_id"
  end

  create_table "professor_week_volunteers", force: :cascade do |t|
    t.string   "surname"
    t.string   "name"
    t.string   "father_name"
    t.integer  "age"
    t.string   "email"
    t.string   "phone"
    t.string   "property"
    t.string   "school"
    t.string   "level"
    t.text     "health"
    t.boolean  "preparation"
    t.boolean  "subscription"
    t.boolean  "updates"
    t.integer  "gender"
    t.boolean  "joined"
    t.datetime "created_at",   null: false
    t.datetime "updated_at",   null: false
  end

  create_table "properties", force: :cascade do |t|
    t.string   "name"
    t.text     "value"
    t.integer  "event_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.integer  "position"
    t.index ["event_id"], name: "index_properties_on_event_id"
  end

  create_table "repetitions", force: :cascade do |t|
    t.datetime "date"
    t.boolean  "time"
    t.datetime "end_date"
    t.decimal  "duration"
    t.integer  "event_id"
    t.datetime "created_at",        null: false
    t.datetime "updated_at",        null: false
    t.integer  "space_override_id"
    t.text     "extra"
    t.boolean  "separate"
    t.index ["event_id"], name: "index_repetitions_on_event_id"
    t.index ["space_override_id"], name: "index_repetitions_on_space_override_id"
  end

  create_table "spaces", force: :cascade do |t|
    t.string   "name"
    t.string   "address"
    t.string   "contact_phone"
    t.integer  "capacity"
    t.text     "technical_details"
    t.text     "logistic_details"
    t.string   "contact_name"
    t.string   "contact_email"
    t.text     "contact_information"
    t.datetime "created_at",          null: false
    t.datetime "updated_at",          null: false
    t.text     "display"
  end

  create_table "submitters", force: :cascade do |t|
    t.text     "surname"
    t.text     "name"
    t.datetime "created_at",  null: false
    t.datetime "updated_at",  null: false
    t.string   "property"
    t.string   "faculty"
    t.string   "school"
    t.string   "phone"
    t.string   "email"
    t.string   "sector"
    t.string   "lab"
    t.string   "phone_other"
  end

  create_table "users", force: :cascade do |t|
    t.string   "email",                             default: "", null: false
    t.string   "encrypted_password",                default: "", null: false
    t.string   "reset_password_token"
    t.datetime "reset_password_sent_at"
    t.datetime "remember_created_at"
    t.integer  "sign_in_count",                     default: 0,  null: false
    t.datetime "current_sign_in_at"
    t.datetime "last_sign_in_at"
    t.string   "current_sign_in_ip"
    t.string   "last_sign_in_ip"
    t.datetime "created_at",                                     null: false
    t.datetime "updated_at",                                     null: false
    t.string   "locale",                 limit: 16
    t.index ["email"], name: "index_users_on_email", unique: true
    t.index ["reset_password_token"], name: "index_users_on_reset_password_token", unique: true
  end

  create_table "volunteers", force: :cascade do |t|
    t.string   "surname"
    t.string   "name"
    t.integer  "age"
    t.string   "email"
    t.string   "phone"
    t.string   "property"
    t.string   "school"
    t.string   "level"
    t.text     "health"
    t.text     "interests"
    t.boolean  "subscription"
    t.datetime "created_at",   null: false
    t.datetime "updated_at",   null: false
    t.boolean  "updates"
    t.integer  "gender"
    t.boolean  "joined"
  end

end
