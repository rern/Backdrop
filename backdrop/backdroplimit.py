#!/usr/bin/python
from backdropgpio import *

# when 'dark' detected -  set pin OFF, broadcast 'id + active'
# when 'light' detected - broadcast 'id + inActive'

url = 'http://localhost/pub?id=backdrop'
headerdata = { 'Content-type': 'application/json', 'Accept': 'application/json' }

def limitChange( i, updn ):
	active = 0
	if updn == 'up':
		if GPIO.input( pinLimitUPlist[ i ] ) == 1: # 1 = dark
			GPIO.output( pinUPlist[ i ], OFF )
			active = 1
	else:
		if GPIO.input( pinLimitDNlist[ i ] ) == 1:
			GPIO.output( pinUPlist[ i ], OFF )
			active = 1
	
	data = { 'pin': updn + str( i ), 'active': active }
	req = urllib2.Request( url, json.dumps( data ), headers = headerdata )
	response = urllib2.urlopen( req )
# bash: curl -s -v -X POST 'http://localhost/pub?id=backdrop' -d '{ "pin": "dn7", "active": 1 }'
	
for i in range( 0, 7 ):
	if pinLimitUPlist[ i ]:
		GPIO.add_event_detect( pinLimitUPlist[ i ], GPIO.BOTH, callback = lambda channel: limitChange( i, 'up' ) )
	if pinLimitDNlist[ i ]:
		GPIO.add_event_detect( pinLimitDNlist[ i ], GPIO.BOTH, callback = lambda channel: limitChange( i, 'dn' ) )
