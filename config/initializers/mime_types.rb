# Be sure to restart your server when you modify this file.

# Add new mime types for use in respond_to blocks:
# Mime::Type.register "text/richtext", :rtf

Mime::Type.register 'text/plain', :txt
Mime::Type.register 'text/plain', :md
Mime::Type.register 'text/csv', :csv, %w( application/csv )
Mime::Type.register "application/vnd.openxmlformats-officedocument.wordprocessingml.document", :docx
Mime::Type.register "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", :xlsx
Mime::Type.register "application/vnd.oasis.opendocument.text", :odt, %w( application/zip )
Mime::Type.register "application/x-latex", :tex, %w( text/tex text/latex application/x-tex ), %w(latex)
