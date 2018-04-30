require_dependency 'custom_audit'

module StevManager
  class Application < Rails::Application
    Audited.config do |config|
      config.audit_class = CustomAudit
    end
  end
end
