require_dependency 'string'

class ImportController < ApplicationController
  before_action :authenticate_user!

  def index
  end

  def upload
    uploaded_io = params[:submissions]
    @data = CSV.parse(uploaded_io.read.force_encoding("utf-8"), encoding: "utf-8")

    parse_events @data
  end

  def upload_volunteers
    uploaded_io = params[:submissions]
    @data = CSV.parse(uploaded_io.read.force_encoding("utf-8"), encoding: "utf-8")

    parse_volunteers @data
  end

  private

  def parse_events(data)
    Encoding.default_external = Encoding::UTF_8

    # Map column_name -> column_id
    co = Hash.new
    data.third.each_index { |k| co[data.third[k]] = k }

    data.select {|x| x.first.to_s.is_i?}.each do |datum|
      @submitter = Submitter.new(
        surname: datum[co["epitheto"]].to_s.force_encoding("utf-8"),
        name: datum[co["onoma"]].to_s.force_encoding("utf-8"),
        property: datum[co["idiotita"]].to_s.force_encoding("utf-8"),
        faculty: datum[co["sholi"]].to_s.force_encoding("utf-8"),
        school: datum[co["tmima"]].to_s.force_encoding("utf-8"),
        phone: datum[co["tilefono_stathero_kinito"]].to_s.force_encoding("utf-8"),
        email: datum[co["e_mail"]].to_s.force_encoding("utf-8")
      )

      @event = Event.new(
        team: datum[co["onoma_foititikis_omadas"]].to_s.force_encoding("utf-8"),
        title: datum[co["titlos_drastiriotitas"]].to_s.force_encoding("utf-8"),
        other: datum[co["an_epilexate_allo3"]].to_s.force_encoding("utf-8"),
        proposed_space: datum[co["horos_diexagogis"]].to_s.force_encoding("utf-8"),
        proposed_time: datum[co["proteinomenes_imerominies"]].to_s.force_encoding("utf-8")
      )

      @event.submitter = @submitter
      kinds = []
      kinds << "theatre" if not datum[co["Θέατρο"]].blank?
      kinds << "music" if not datum[co["Μουσική"]].blank?
      kinds << "photography" if not datum[co["Φωτογραφία"]].blank?
      kinds << "sports" if not datum[co["Αθλητισμός"]].blank?
      kinds << "other" if not datum[co["Άλλο"]].blank?
      @event.kind = kinds.first
      @event.fields = kinds

      @submitter.save
      @event.save
    end
  end

  def parse_volunteers(data)
    Encoding.default_external = Encoding::UTF_8

    data.select {|x| x.first.to_s.is_i?}.each do |datum|
      @volunteer = Volunteer.new(
          surname: datum[7].to_s.force_encoding("utf-8"),
          name: datum[8].to_s.force_encoding("utf-8"),
          age: datum[9].to_s.force_encoding("utf-8"),
          email: datum[10].to_s.force_encoding("utf-8"),
          phone: datum[11].to_s.force_encoding("utf-8"),
          property: datum[12].to_s.force_encoding("utf-8"),
          school: datum[13].to_s.force_encoding("utf-8"),
          level: datum[14].to_s.force_encoding("utf-8"),
          health: datum[15].to_s.force_encoding("utf-8"),
          interests: datum[16].to_s.force_encoding("utf-8"),
          subscription: datum[17].to_s.force_encoding("utf-8")
      )

      @volunteer.save
    end
  end

end
