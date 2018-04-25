module SurveysHelper
  def flatten_name(name)
    converter = Greeklish.converter(max_expansions: 3)

    name.mb_chars.downcase.to_s
        .gsub('ά','α')
        .gsub('έ','ε')
        .gsub('ή','η')
        .gsub('ί','ι')
        .gsub('ό','ο')
        .gsub('ύ','υ')
        .gsub('ώ','ω')
        .gsub('ϋ','υ')
        .gsub('ϊ','ι')
        .gsub('ΰ','υ')
        .gsub('ΐ','ι').split(/ +/).map{|st| converter.convert(st) || st}.flatten.uniq
  end

  def locate_volunteer_by_name(name)
    # Split the given name into parts
    parts = flatten_name name

    matched = []
    ProfessorWeek::Volunteer.all.each do |volunteer|
      surnames = flatten_name volunteer.surname

      unless (parts & surnames).empty?
        matched.push volunteer
      end
    end

    if matched.length > 1
      # Too many matches! Try to reduce them
      old_matched = matched
      matched = []
      old_matched.each do |volunteer|
        names = flatten_name volunteer.name
        puts names
        unless (parts & names).empty?
          matched.push volunteer
        end
      end
    end

    matched
  end
end
