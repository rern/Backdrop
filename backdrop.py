#!/usr/bin/python
import RPi.GPIO as GPIO
import sys
import time
import json

ON = 1
OFF = 0

pinUPlist = [  3, 16, 19, 21, 22, 23, 11 ]
pinDNlist = [ 13, 26, 29, 31, 32, 33, 15 ]

GPIO.setwarnings( 0 )
GPIO.setmode( GPIO.BOARD )
GPIO.setup( pinUPlist + pinDNlist, GPIO.OUT )

arg1 = sys.argv[ 1 ]
if arg1 == 'state':
	stateON = []
	for i in range( 0, 7 ):
		if GPIO.input( pinUPlist[ i ] ) == 1:
			stateON.append( 'up'+ str( i + 1 ) )
		if GPIO.input( pinDNlist[ i ] ) == 1:
			stateON.append( 'dn'+ str( i + 1 ) )

	print( json.dumps( stateON ) )
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
	
second = float( sys.argv[ 2 ] )

GPIO.output( pin, ON )
time.sleep( second )
GPIO.output( pin, OFF )
