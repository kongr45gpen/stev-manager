from pygments import highlight, lexers, formatters
import json

output = open('schedule.ruh.aux','w')
input  = open('schedule.rul.aux','r')

oldline = None
objects = []
for row in input:
  event, line = row.split()

  broken = (line != oldline) and (oldline != None)

  object = {'event':event, 'line':line,'broken':broken}
  objects.append(object)

  oldline = line

if objects:
  id = objects[-1]['event']
  objects.append({'event': str(int(id)+1), 'line':'-1', 'broken': True}) # last event gets no horizontal line

for object in objects:
  formatted_json = json.dumps(object)
  colorful_json = highlight(unicode(formatted_json, 'UTF-8'), lexers.JsonLexer(), formatters.TerminalFormatter())
  print colorful_json,

  if object['broken']:
    output.write(object['event'] + "\n")

input.close()
output.close()
