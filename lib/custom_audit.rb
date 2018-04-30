class CustomAudit < Audited::Audit
end

class Audited::Audit
  def display_name
    id.to_s + ' ' + auditable.public_send([:display_name,:full_name,:name,:to_s].select{|m| auditable.respond_to? m}.first)
  end
end