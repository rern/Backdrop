#!/usr/bin/python

# limit: DN by infrared, UP by timer
# 'dark marker' detected > set pin OFF, broadcast 'buttonid + active 1' > disable button
# 'no marker'   detected >              broadcast 'buttonid + active 0' > enable button

from backdropgpio import *

def limitChange( pin, i ):
	GPIO.output( pinDNlist[ i ], OFF )
	
	data = { 'updn': 'dn', 'num': i + 1 }
	req = urllib2.Request( url, json.dumps( data ), headers = headerdata )
	response = urllib2.urlopen( req )
	# bash: curl -s -v -X POST 'http://localhost/pub?id=backdrop' -d '{ "updn": "dn", "num": 7, "active": 1 }'
	
for i in range( 0, 7 ):
	pin = pinLimitlist[ i ]
	GPIO.add_event_detect( pin, GPIO.FALLING, callback = lambda channel: limitChange( pin, i ), bouncetime = 300 )

while True:
	time.sleep( 10 )
