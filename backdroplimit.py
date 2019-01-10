#!/usr/bin/python
import RPi.GPIO as GPIO
import json
import urllib
import urllib2

ON = 1
OFF = 0

#pinOFFlist = [ 7,  8, 10, 12, 13, 15, 16 ]
#pinUPlist = [ 18, 19, 21, 22, 23, 24, 26 ]
pinOFFlist = [ 3,  7,  8, 10, 12, 18, 24 ]
pinDNlist = [ 13, 26, 29, 31, 32, 33, 15 ]

GPIO.setwarnings( 0 )
GPIO.setmode( GPIO.BOARD )
GPIO.setup( pinOFFist, GPIO.IN )

def pinOff( i ):  
	GPIO.output( pinDNlist[ i ], OFF )
	# broadcast pushstream
	url = 'http://localhost/pub?id=backdrops'
	headerdata = { 'Content-type': 'application/json', 'Accept': 'application/json' }
	data = { 'pin': 'dn'+ str( i ) }
	req = urllib2.Request( url, json.dumps( data ), headers = headerdata )
	response = urllib2.urlopen( req )
		
for i in range( 0, 7 ):
	GPIO.add_event_detect( pinOFFlist[ i ], GPIO.RISING, callback = pinOff( i ) )
