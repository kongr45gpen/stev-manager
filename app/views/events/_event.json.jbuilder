json.extract! event, :id, :team, :title, :kind, :other, :proposed_space, :proposed_time, :submitter_id, :created_at, :updated_at
json.url event_url(event, format: :json)
