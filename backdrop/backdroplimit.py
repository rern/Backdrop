#!/usr/bin/python
import RPi.GPIO as GPIO
import json
import urllib
import urllib2

ON = 1
OFF = 0

# 26 avilable pins:
#pinUPoffList = [  3,  5,  7,  8, 10, 11 ]  # 6 pins
#pinDNoffList = [ 12, 13, 15, 16, 18, 19 ]  # 6 pins
#pinUPlist = [ 21, 22, 23, 24, 26, 29, 31 ] # 7 pins
#pinDNlist = [ 32, 33, 35, 36, 37, 38, 40 ] # 7 pins
pinOFFlist = [ 7,  8, 10, 12, 18, 24, 35 ]
pinUPlist = [  3, 16, 19, 21, 22, 23, 11 ]
pinDNlist = [ 13, 26, 29, 31, 32, 33, 15 ]

GPIO.setwarnings( 0 )
GPIO.setmode( GPIO.BOARD )
GPIO.setup( pinOFFist, GPIO.IN )

def pinOff( i ):  
	GPIO.output( pinDNlist[ i ], OFF )
	# broadcast pushstream
	url = 'http://localhost/pub?id=backdrop'
	headerdata = { 'Content-type': 'application/json', 'Accept': 'application/json' }
	data = { 'pin': 'dn'+ str( i ) }
	req = urllib2.Request( url, json.dumps( data ), headers = headerdata )
	response = urllib2.urlopen( req )
		
for i in range( 0, 7 ):
	GPIO.add_event_detect( pinOFFlist[ i ], GPIO.RISING, callback = pinOff( i ) )
