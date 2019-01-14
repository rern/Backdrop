#!/usr/bin/python

# 'dark marker' detected > set pin OFF, broadcast 'buttonid + active 1' > disable button
# 'no marker'   detected >              broadcast 'buttonid + active 0' > enable button

from backdropgpio import *

url = 'http://localhost/pub?id=backdrop'
headerdata = { 'Content-type': 'application/json', 'Accept': 'application/json' }

def limitChange( pin, i ):
	if GPIO.input( pin ): # 1 = dark
		GPIO.output( pinDNlist[ i ], OFF )
		active = 1
	else:
		active = 0
	
	data = { 'updnid': 'dn'+ str( i ), 'active': active }
	req = urllib2.Request( url, json.dumps( data ), headers = headerdata )
	response = urllib2.urlopen( req )
	
	print( 'dn'+ str( i ) +' '+ str( active ) )
		
# bash: curl -s -v -X POST 'http://localhost/pub?id=backdrop' -d '{ "updnid": "dn7", "active": 1 }'
	
for i in range( 0, 7 ):
	pin = pinLimitlist[ i ]
	GPIO.add_event_detect( pin, GPIO.BOTH, callback = lambda channel: limitChange( pin, i ), bouncetime = 300 )

while True:
	time.sleep( 10 )
