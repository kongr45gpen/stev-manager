module VolunteersHelper
end

class String
  def only_upper_case
    self.scan(/\p{Upper}/).drop(1).join
  end

  def to_colour
    '#' + Digest::MD5.hexdigest(self)[0..5]
  end
end