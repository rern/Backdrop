#!/usr/bin/python

# Auto, large buttons, always OFF at limits
# Maual, small buttons, can keep ON pass limits
# Both auto and manual have OFF timers in case limits not detected

from backdropgpio import *
import sys
import time

arg1 = sys.argv[ 1 ]
if arg1 == 'set':
	GPIO.output( pinList, OFF )
	exit()
	
if arg1 == 'state':
	onList = []
	limitActiveList = []
	for i in range( 0, 7 ):
		if pinUPlist[ i ] and GPIO.input( pinUPlist[ i ] ) == ON:
			onList.append( 'up'+ str( i + 1 ) )
		if pinDNlist[ i ] and GPIO.input( pinDNlist[ i ] ) == ON:
			onList.append( 'dn'+ str( i + 1 ) )
		if pinLimitUPlist[ i ] and GPIO.input( pinLimitUPlist[ i ] ) == ON:
			limitActiveList.append( 'up'+ str( i + 1 ) )
		if pinLimitDNlist[ i ] and GPIO.input( pinLimitDNlist[ i ] ) == ON:
			limitActiveList.append( 'dn'+ str( i + 1 ) )

	print( json.dumps( { 'on': onList, 'limitActive': limitActiveList } ) )
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
	
# prevent auto if limit already reach
if UpDn == 'up' and GPIO.input( pinLimitUPlist[ i ] ) == ON:
	exit()
if UpDn == 'dn' and GPIO.input( pinLimitDNlist[ i ] ) == ON:
	exit()
	
second = float( sys.argv[ 2 ] )

GPIO.output( pin, ON )
time.sleep( second )
GPIO.output( pin, OFF )

# testing only
url = 'http://localhost/pub?id=backdrop'
headerdata = { 'Content-type': 'application/json', 'Accept': 'application/json' }
data = { 'pin': arg1, 'active': 1 }
req = urllib2.Request( url, json.dumps( data ), headers = headerdata )
response = urllib2.urlopen( req )
