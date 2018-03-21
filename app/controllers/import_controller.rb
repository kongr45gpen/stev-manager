require_dependency 'string'

class ImportController < ApplicationController
  before_action :authenticate_user!

  def index; end

  def upload
    uploaded_io = params[:submissions]
    @data = CSV.parse(uploaded_io.read.force_encoding('utf-8'), encoding: 'utf-8')

    parse_events @data
  end

  def upload_pw_events
    uploaded_io = params[:submissions]
    @data = CSV.parse(uploaded_io.read.force_encoding('utf-8'), encoding: 'utf-8')

    parse_pw_events @data

    render 'upload'
  end

  def upload_volunteers
    uploaded_io = params[:submissions]
    @data = CSV.parse(uploaded_io.read.force_encoding('utf-8'), encoding: 'utf-8')

    parse_volunteers @data

    render 'upload'
  end

  def upload_pw_volunteers
    uploaded_io = params[:submissions]
    @data = CSV.parse(uploaded_io.read.force_encoding('utf-8'), encoding: 'utf-8')

    parse_pw_volunteers @data

    render 'upload'
  end

  private

  def parse_events(data)
    Encoding.default_external = Encoding::UTF_8

    # Map column_name -> column_id
    co = {}
    data.third.each_index { |k| co[data.third[k]] = k }

    data.select { |x| x.first.to_s.is_i? }.each do |datum|
      @submitter = Submitter.new(
        surname: datum[co['epitheto']].to_s.force_encoding('utf-8'),
        name: datum[co['onoma']].to_s.force_encoding('utf-8'),
        property: datum[co['idiotita']].to_s.force_encoding('utf-8'),
        faculty: datum[co['sholi']].to_s.force_encoding('utf-8'),
        school: datum[co['tmima']].to_s.force_encoding('utf-8'),
        phone: datum[co['tilefono_stathero_kinito']].to_s.force_encoding('utf-8'),
        email: datum[co['e_mail']].to_s.force_encoding('utf-8')
      )

      @event = Event.new(
        team: datum[co['onoma_foititikis_omadas']].to_s.force_encoding('utf-8'),
        title: datum[co['titlos_drastiriotitas']].to_s.force_encoding('utf-8'),
        other: datum[co['an_epilexate_allo3']].to_s.force_encoding('utf-8'),
        proposed_space: datum[co['horos_diexagogis']].to_s.force_encoding('utf-8'),
        proposed_time: datum[co['proteinomenes_imerominies']].to_s.force_encoding('utf-8')
      )

      @event.submitter = @submitter
      kinds = []
      kinds << 'theatre' unless datum[co['Θέατρο']].blank?
      kinds << 'music' unless datum[co['Μουσική']].blank?
      kinds << 'photography' unless datum[co['Φωτογραφία']].blank?
      kinds << 'sports' unless datum[co['Αθλητισμός']].blank?
      kinds << 'other' unless datum[co['Άλλο']].blank?
      @event.kind = kinds.first
      @event.fields = kinds

      @submitter.save
      @event.save
    end
  end

  def parse_pw_events(data)
    Encoding.default_external = Encoding::UTF_8

    # Map column_name -> column_id
    co = {}
    data.third.each_index { |k| co[data.third[k]] = k }
    data.map! do |datum|
      new_datum = {}
      co.each { |point| new_datum[point.first] = datum[point.second] }

      new_datum
    end

    data.select { |x| x['webform_serial'].to_s.is_i? }.each do |datum|
      @submitters = []
      @submitters[0] = Submitter.new(
        surname: datum['epitheto'].to_s.force_encoding('utf-8').strip,
        name: datum['onoma'].to_s.force_encoding('utf-8').strip,
        property: datum['idiotita'].to_s.force_encoding('utf-8').strip,
        faculty: datum['sholi'].to_s.force_encoding('utf-8').strip,
        school: datum['tmima'].to_s.force_encoding('utf-8').strip,
        sector: datum['tomeas'].to_s.force_encoding('utf-8').strip,
        lab: datum['ergastirio'].to_s.force_encoding('utf-8').strip,
        phone: datum['tilefono_stathero1'].to_s.force_encoding('utf-8').strip,
        phone_other: datum['tilefono_kinito1'].to_s.force_encoding('utf-8').strip,
        email: datum['e_mail'].to_s.force_encoding('utf-8').strip,
      )
      @submitters[1] = Submitter.new(
        surname: datum['epitheto2'].to_s.force_encoding('utf-8').strip,
        name: datum['onoma2'].to_s.force_encoding('utf-8').strip,
        property: datum['idiotita2'].to_s.force_encoding('utf-8').strip,
        faculty: datum['sholi2'].to_s.force_encoding('utf-8').strip,
        school: datum['tmima2'].to_s.force_encoding('utf-8').strip,
        sector: datum['tomeas2'].to_s.force_encoding('utf-8').strip,
        lab: datum['ergastirio2'].to_s.force_encoding('utf-8').strip,
        phone: datum['tilefono_stathero2'].to_s.force_encoding('utf-8').strip,
        phone_other: datum['tilefono_kinito2'].to_s.force_encoding('utf-8').strip,
        email: datum['email3'].to_s.force_encoding('utf-8').strip,
        )
      @submitters[2] = Submitter.new(
        surname: datum['epitheto2'].to_s.force_encoding('utf-8').strip,
        name: datum['onoma3'].to_s.force_encoding('utf-8').strip,
        property: datum['idiotita3'].to_s.force_encoding('utf-8').strip,
        faculty: datum['sholi3'].to_s.force_encoding('utf-8').strip,
        school: datum['tmima3'].to_s.force_encoding('utf-8').strip,
        sector: datum['tomeas3'].to_s.force_encoding('utf-8').strip,
        lab: datum['ergastirio3'].to_s.force_encoding('utf-8').strip,
        phone: datum['tilefono_stathero3'].to_s.force_encoding('utf-8').strip,
        phone_other: datum['tilefono_kinito3'].to_s.force_encoding('utf-8').strip,
        email: datum['email4'].to_s.force_encoding('utf-8').strip,
        )
      @submitters.select! do |sub|
        !sub['surname'].empty? || !sub['name'].empty? || !sub['property'].empty? || !sub['faculty'].empty? \
        || !sub['school'].empty? || !sub['sector'].empty? || !sub['lab'].empty? || !sub['phone'].empty? \
        || !sub['phone_other'].empty? || !sub['email'].empty?
      end


      @event = ProfessorWeek::Event.new(
        title: datum['titlos_drastiriotitas'].to_s.force_encoding('utf-8'),
        # father_name: datum['patronymo'].to_s.force_encoding('utf-8'),
        # age: datum['ilikia'].to_s.force_encoding('utf-8'),
        # email: datum['email'].to_s.force_encoding('utf-8'),
        # phone: datum['til'].to_s.force_encoding('utf-8'),
        # property: datum['idiotita'].to_s.force_encoding('utf-8'),
        # school: datum['sholi_tmima'].to_s.force_encoding('utf-8'),
        # level: datum['epipedo_spoydon'].to_s.force_encoding('utf-8'),
        # health: (datum['ehete_kapoio_iatriko_thema'].to_s.force_encoding('utf-8') != 'ΟΧΙ' ? datum['ehete_kapoio_iatriko_thema'].to_s.force_encoding('utf-8') + '. ' : '') +datum['parakalo_dieykriniste'].to_s.force_encoding('utf-8'),
        # subscription: datum['notifications'].to_s.force_encoding('utf-8') == 'ΝΑΙ',
        # preparation: datum['symmeteho_proetoimasia'].to_s.force_encoding('utf-8') == 'ΝΑΙ'
      )

      kinds = []
      kinds << 'experiment' unless datum['Πείραμα'].blank?
      kinds << 'observation' unless datum['Παρατήρηση'].blank?
      kinds << 'lab' unless datum['Δημιουργικό εργαστήριο'].blank?
      kinds << 'presentation' unless datum['Παρουσίαση'].blank?
      kinds << 'archive' unless datum['Μουσείο - Αρχείο - Συλλογή'].blank?
      kinds << 'game' unless datum['Παιχνίδι'].blank?
      kinds << 'demonstration' unless datum['Επίδειξη'].blank?
      @event.fields = kinds

      @event.save
    end
  end

  def parse_volunteers(data)
    Encoding.default_external = Encoding::UTF_8

    # Map column_name -> column_id
    co = {}
    data.third.each_index { |k| co[data.third[k]] = k }
    data.map! do |datum|
      new_datum = {}
      co.each { |point| new_datum[point.first] = datum[point.second] }

      new_datum
    end

    data.select { |x| x['webform_serial'].to_s.is_i? }.each do |datum|
      last_in_name = datum['onoma'].to_s.force_encoding('utf-8').strip[-1].downcase
      @volunteer = Volunteer.new(
        surname: datum['epitheto'].to_s.force_encoding('utf-8'),
        name: datum['onoma'].to_s.force_encoding('utf-8'),
        age: datum['ilikia'].to_s.force_encoding('utf-8'),
        email: datum['email'].to_s.force_encoding('utf-8'),
        phone: datum['til'].to_s.force_encoding('utf-8'),
        property: datum['idiotita'].to_s.force_encoding('utf-8'),
        school: datum['sholi_tmima'].to_s.force_encoding('utf-8'),
        level: datum['epipedo_spoydon'].to_s.force_encoding('utf-8'),
        health: datum['ehete_kapoio_iatriko_thema'].to_s.force_encoding('utf-8'),
        subscription: datum['notifications'].to_s.force_encoding('utf-8') == 'ΝΑΙ',
        gender: last_in_name == 'σ' || last_in_name == 'ς' || last_in_name == 's' ? Volunteer.genders[:male] : Volunteer.genders[:female]
      )

      @volunteer.save
    end
  end


  def parse_pw_volunteers(data)
    Encoding.default_external = Encoding::UTF_8

    # Map column_name -> column_id
    co = {}
    data.third.each_index { |k| co[data.third[k]] = k }
    data.map! do |datum|
      new_datum = {}
      co.each { |point| new_datum[point.first] = datum[point.second] }

      new_datum
    end
    
    data.select { |x| x['webform_serial'].to_s.is_i? }.each do |datum|
      last_in_name = datum['onoma'].to_s.force_encoding('utf-8').strip[-1].downcase
      @volunteer = ProfessorWeek::Volunteer.new(
        surname: datum['epitheto'].to_s.force_encoding('utf-8'),
        name: datum['onoma'].to_s.force_encoding('utf-8'),
        father_name: datum['patronymo'].to_s.force_encoding('utf-8'),
        age: datum['ilikia'].to_s.force_encoding('utf-8'),
        email: datum['email'].to_s.force_encoding('utf-8'),
        phone: datum['til'].to_s.force_encoding('utf-8'),
        property: datum['idiotita'].to_s.force_encoding('utf-8'),
        school: datum['sholi_tmima'].to_s.force_encoding('utf-8'),
        level: datum['epipedo_spoydon'].to_s.force_encoding('utf-8'),
        health: (datum['ehete_kapoio_iatriko_thema'].to_s.force_encoding('utf-8') != 'ΟΧΙ' ? datum['ehete_kapoio_iatriko_thema'].to_s.force_encoding('utf-8') + '. ' : '') +datum['parakalo_dieykriniste'].to_s.force_encoding('utf-8'),
        subscription: datum['notifications'].to_s.force_encoding('utf-8') == 'ΝΑΙ',
        preparation: datum['symmeteho_proetoimasia'].to_s.force_encoding('utf-8') == 'ΝΑΙ',
        gender: last_in_name == 'σ' || last_in_name == 'ς' || last_in_name == 's' ? Volunteer.genders[:male] : Volunteer.genders[:female]
      )

      @volunteer.save
    end
  end
end
