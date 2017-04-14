# Be sure to restart your server when you modify this file.

# Add new mime types for use in respond_to blocks:
# Mime::Type.register "text/richtext", :rtf

Mime::Type.register "application/x-latex", :tex, %w( text/tex text/latex application/x-tex ), %w(latex)