require_dependency 'string'

class ImportController < ApplicationController
  before_action :authenticate_user!

  def index; end

  def upload
    uploaded_io = params[:submissions]
    @data = CSV.parse(uploaded_io.read.force_encoding('utf-8'), encoding: 'utf-8')

    parse_events @data
  end

  def upload_volunteers
    uploaded_io = params[:submissions]
    @data = CSV.parse(uploaded_io.read.force_encoding('utf-8'), encoding: 'utf-8')

    parse_volunteers @data

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
        level: datum['epipedo_spoudon'].to_s.force_encoding('utf-8'),
        health: datum['ehete_kapoio_iatriko_thema'].to_s.force_encoding('utf-8'),
        subscription: datum['notifications'].to_s.force_encoding('utf-8'),
        gender: last_in_name == 'σ' || last_in_name == 'ς' || last_in_name == 's' ? Volunteer.genders[:male] : Volunteer.genders[:female]
      )

      @volunteer.save
    end
  end
end
