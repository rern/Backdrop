#!/usr/bin/python

# limit: DN by infrared, UP by timer
# 'dark marker' detected > set pin OFF, broadcast 'buttonid + active 1' > disable button
# 'no marker'   detected >              broadcast 'buttonid + active 0' > enable button

from backdropgpio import *

def limitActive( UpDn, i ): 
	if UpDn = 'up':
		if GPIO.input( pinUpLimitList[ i ] ):
			GPIO.output( pinUpList[ i ], OFF )
			active = 1
		else:
			active = 0;
	else:
		if GPIO.input( pinDnLimitList[ i ] ):
			GPIO.output( pinDnList[ i ], OFF )
			active = 1
		else:
			active = 0;
	
	data = { 'UpDn': UpDn, 'num': i + 1, 'active': active }
	req = urllib2.Request( url, json.dumps( data ), headers = headerdata )
	response = urllib2.urlopen( req )
	# bash: curl -s -v -X POST 'http://localhost/pub?id=backdrop' -d '{ "UpDn": "dn", "num": 7 }'
	
for i in range( 0, 7 ):
	pin = pinUpLimitList[ i ]
	if pin:
		GPIO.add_event_detect( pin, GPIO.BOTH, callback = lambda channel: limitActive( 'up', i ), bouncetime = 300 )
	pin = pinDnLimitList[ i ]
	if pin:
		GPIO.add_event_detect( pin, GPIO.BOTH, callback = lambda channel: limitActive( 'dn', i ), bouncetime = 300 )

while True:
	time.sleep( 10 )
