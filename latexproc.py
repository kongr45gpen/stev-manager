from pygments import highlight, lexers, formatters
import json

output = open('schedule.ruh.aux','w')
input  = open('schedule.rul.aux','r')

oldline = None
for row in input:
  event, line = row.split()

  broken = (line != oldline) and (oldline != None)

  formatted_json = json.dumps({'event':event, 'line':line,'broken':broken})
  colorful_json = highlight(unicode(formatted_json, 'UTF-8'), lexers.JsonLexer(), formatters.TerminalFormatter())
  print colorful_json,

  oldline = line

  if broken:
    output.write(event + "\n")

input.close()
output.close()
