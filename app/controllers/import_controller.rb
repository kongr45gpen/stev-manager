require_dependency 'string'

class ImportController < ApplicationController
  before_action :authenticate_user!

  def index;
  end

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

  def process_pw_repetitions
    ProfessorWeek::Event.all.each do |event|
      event.repetitions = [] # clear event repetitions

      unless event.date_start
        next
      end

      # Do not trust date_repetition_count
      repetition_count = ((event.date_duration_total.presence || 0) / (event.date_duration.nonzero? || 1)).floor
      event.date_dates_dates.each do |d|
        (1..repetition_count).each do |r|
          time = event.date_start + (r-1) * event.date_duration_total.minutes / repetition_count

          repetition = ProfessorWeek::Repetition.new(
            date: DateTime.new(d.year, d.month, d.day, time.hour, time.min, time.sec),
            duration: event.date_duration,
            event: event
          )

          repetition.save
        end
      end
    end
  end

  private

  def parse_events(data)
    Encoding.default_external = Encoding::UTF_8

    # Map column_name -> column_id
    co = {}
    data.third.each_index {|k| co[data.third[k]] = k}

    data.select {|x| x.first.to_s.is_i?}.each do |datum|
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
    data.third.each_index {|k| co[data.third[k]] = k}
    data.map! do |datum|
      new_datum = {}
      co.each {|point| new_datum[point.first] = datum[point.second]}

      new_datum
    end

    data.select {|x| x['webform_serial'].to_s.is_i?}.each do |datum|
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
          surname: datum['epitheto3'].to_s.force_encoding('utf-8').strip,
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

      details_dates = ""
      dates_list = []
      dates_list.push('6/5/2018') unless datum[' 6 Μαΐου'].blank?
      dates_list.push('13/5/2018') unless datum[' 13 Μαΐου'].blank?
      dates_list.push('20/5/2018') unless datum[' 20 Μαΐου'].blank?
      details_dates += dates_list.join(', ') + "\n"
      details_dates += 'Αριθμός Φορών: ' + (datum['arithmos_epanalipseon'].to_i + 1).to_s + "\n"
      details_dates += datum['an_epilexate_allo'].to_s.force_encoding('utf-8') + "\n"
      details_dates += 'Διάρκεια' + "\n"
      details_dates += '  Επανάληψης (min): ' + datum['diarkeia_drastiriotitas2'].to_s.force_encoding('utf-8') + "\n"
      details_dates += '  Συνολική (min): ' + datum['diarkeia_synoliki'].to_s.force_encoding('utf-8') + "\n"
      details_dates += 'Ώρα Έναρξης: ' + datum['ora_enarxis_tis_drasis'].to_s.force_encoding('utf-8') + "\n"

      @event = ProfessorWeek::Event.new(
          title: datum['titlos_drastiriotitas'].to_s.force_encoding('utf-8'),
          details_space: datum['horos_diexagogis'].to_s.force_encoding('utf-8'),
          # details_dates: details_dates,
          ages: datum['koino_sto_opoio_apethynetai'],
          registration_required: datum['apaiteitai_dilosi_symmetohis'].to_s.force_encoding('utf-8') != 'ΟΧΙ',
          registration_email: datum['ilektronika'].to_s.force_encoding('utf-8'),
          registration_max: datum['megistos_arithmos_symmetehonton_ana_epanalipsi'].to_s.force_encoding('utf-8'),
          registration_deadline: datum['teliki_imerominia_kataxwrisis_dilwsewn'].to_s.force_encoding('utf-8'),
          details_costs: datum['kostos_analosimon'].to_s.force_encoding('utf-8'),
          collaborator_count: datum['arithmos_synergaton'].to_s.force_encoding('utf-8'),
          student_count: datum['arithmos_foititwn'].to_s.force_encoding('utf-8'),
          volunteer_count: datum['arithmos_epipleon_ethelontwn'].to_s.force_encoding('utf-8'),
          description: datum['syntomi_perigrafi_protasis'].to_s.force_encoding('utf-8'),
          abstract: datum['syntomi_perigrafi30_lexeis'].to_s.force_encoding('utf-8'),
          date_repetition_count: datum['arithmos_epanalipseon'].to_i + 1,
          date_repetition_other: datum['an_epilexate_allo'].to_s.force_encoding('utf-8'),
          date_duration: datum['diarkeia_drastiriotitas2'],
          date_start: datum['ora_enarxis_tis_drasis'].to_s.force_encoding('utf-8'),
          date_duration_total: datum['diarkeia_synoliki'].to_s.force_encoding('utf-8'),
          date_dates: dates_list
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

      if @event.save
        @submitters.each {|submitter| @event.submitters << submitter}
      end
    end
  end

  def parse_volunteers(data)
    Encoding.default_external = Encoding::UTF_8

    # Map column_name -> column_id
    co = {}
    data.third.each_index {|k| co[data.third[k]] = k}
    data.map! do |datum|
      new_datum = {}
      co.each {|point| new_datum[point.first] = datum[point.second]}

      new_datum
    end

    data.select {|x| x['webform_serial'].to_s.is_i?}.each do |datum|
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
    data.third.each_index {|k| co[data.third[k]] = k}
    data.map! do |datum|
      new_datum = {}
      co.each {|point| new_datum[point.first] = datum[point.second]}

      new_datum
    end

    data.select {|x| x['webform_serial'].to_s.is_i?}.each do |datum|
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
          health: (datum['ehete_kapoio_iatriko_thema'].to_s.force_encoding('utf-8') != 'ΟΧΙ' ? datum['ehete_kapoio_iatriko_thema'].to_s.force_encoding('utf-8') + '. ' : '') + datum['parakalo_dieykriniste'].to_s.force_encoding('utf-8'),
          subscription: datum['notifications'].to_s.force_encoding('utf-8') == 'ΝΑΙ',
          preparation: datum['symmeteho_proetoimasia'].to_s.force_encoding('utf-8') == 'ΝΑΙ',
          gender: last_in_name == 'σ' || last_in_name == 'ς' || last_in_name == 's' ? Volunteer.genders[:male] : Volunteer.genders[:female]
      )

      @volunteer.save
    end
  end
end
