require_dependency 'string'

class ImportController < ApplicationController
  def index
  end

  def upload
    uploaded_io = params[:submissions]
    @data = CSV.parse(uploaded_io.read, encoding: "utf-8")

    parse @data
  end

  private

  def parse(data)
    surname = 7
    name = 8
    property = 9
    faculty = 10
    school = 11
    phone = 12
    email = 13
    team = 14
    title = 15
    theatre = 16
    music = 17
    photography = 18
    sports = 19
    other = 20
    other_text = 21
    proposed_space = 22
    proposed_time = 23

    Encoding.default_external = Encoding::UTF_8

    @data.select {|x| x.first.to_s.is_i?}.each do |datum|
      @submitter = Submitter.new(
        surname: datum[7].to_s.force_encoding("utf-8"),
        name: datum[8].to_s.force_encoding("utf-8"),
        property: datum[9].to_s.force_encoding("utf-8"),
        faculty: datum[10].to_s.force_encoding("utf-8"),
        school: datum[11].to_s.force_encoding("utf-8"),
        phone: datum[12].to_s.force_encoding("utf-8"),
        email: datum[13].to_s.force_encoding("utf-8")
      )

      @event = Event.new(
        team: datum[14].to_s.force_encoding("utf-8"),
        title: datum[15].to_s.force_encoding("utf-8"),
        other: datum[21].to_s.force_encoding("utf-8"),
        proposed_space: datum[22].to_s.force_encoding("utf-8"),
        proposed_time: datum[23].to_s.force_encoding("utf-8")
      )

      @event.submitter = @submitter
      kinds = []
      kinds << "theatre" if datum[16]
      kinds << "music" if datum[17]
      kinds << "photography" if datum[18]
      kinds << "sports" if datum[19]
      kinds << "other" if datum[20]
      @event.kind = kinds.first
      @event.fields = kinds

      # return @submitter

      @submitter.save
      @event.save
    end
  end

end
