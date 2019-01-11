#!/usr/bin/python
import RPi.GPIO as GPIO
import sys
import time
import json
import urllib
import urllib2

ON = 1
OFF = 0

pinOFFlist = [ 7,  8, 10, 12, 18, 24, 35 ]
pinUPlist = [  3, 16, 19, 21, 22, 23, 11 ]
pinDNlist = [ 13, 26, 29, 31, 32, 33, 15 ]
pinList = pinUPlist + pinDNlist

GPIO.setwarnings( 0 )
GPIO.setmode( GPIO.BOARD )
GPIO.setup( pinList, GPIO.OUT )
GPIO.setup( pinOFFlist, GPIO.IN )

arg1 = sys.argv[ 1 ]
if arg1 == 'set':
	GPIO.output( List, OFF )
	exit()
	
if arg1 == 'state':
	stateON = []
	stateDN = []
	for i in range( 0, 7 ):
		if GPIO.input( pinUPlist[ i ] ) == ON:
			stateON.append( 'up'+ str( i + 1 ) )
		if GPIO.input( pinDNlist[ i ] ) == ON:
			stateON.append( 'dn'+ str( i + 1 ) )
		if GPIO.input( pinOFFlist[ i ] ) == ON:
			stateDN.append( str( i + 1 ) )

	print( json.dumps( { 'on': stateON, 'dn': stateDN } ) )
	exit()

UpDn = arg1[ :2 ]
i = int( arg1[ -1: ] ) - 1

if UpDn == 'up':
	pin = pinUPlist[ i ]
else:
	pin = pinDNlist[ i ]

if len( sys.argv ) == 2:
	GPIO.output( pin, OFF )
	exit()
	
if UpDn == 'dn' and GPIO.input( pinOFFlist[ i ] ) == ON:
	exit()
	
second = float( sys.argv[ 2 ] )

GPIO.output( pin, ON )
time.sleep( second )
GPIO.output( pin, OFF )
# broadcast pushstream
url = 'http://localhost/pub?id=backdrop'
headerdata = { 'Content-type': 'application/json', 'Accept': 'application/json' }
data = { 'pin': arg1 }
req = urllib2.Request( url, json.dumps( data ), headers = headerdata )
response = urllib2.urlopen( req )
