module ScheduleHelper
  include EventsHelper

  def markdown_source
    ScheduleController.new.render_to_string(
        "schedule/index.md.erb", locals: { :@events => @events }
    )
  end

  def markdown_source_pw
    ScheduleController.new.render_to_string(
        "schedule/index_pw.md.erb", locals: { :@events => @events }
    )
  end
end

class String
  def texcape
    self.gsub('&','\\\\&').gsub('_','\\\\_').html_safe
  end

  def detex
    self.gsub(/\\textbf{([^}]*)}/, '<strong>\1</strong>')
        .gsub(/\\emph{([^}]*)}/,   '<em>\1</em>')
        .gsub(/\\texttt{([^}]*)}/, '<code>\1</code>')
        .gsub(/\\url{([^}]*)}/,  '<a href="\1"><code>\1</code></a>')
        .gsub(/\\href{([^}]*)}{([^}]*)}/,  '<a href="\1">\2</a>')
        .gsub(/\\\\/, '<br>')
        .html_safe
  end
end

class NilClass
  def empty?
    true
  end
end