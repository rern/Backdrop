#!/usr/bin/python
import RPi.GPIO as GPIO
import sys
import time

ON = 1
OFF = 0

pinUP = 11
pinDN = 13
pinLimitUP = 15
pinLimitDN = 16
pins = [ pinUP, pinDN ]
pinLimits = [ pinLimitUP, pinLimitDN ]

GPIO.setwarnings( 0 )
GPIO.setmode( GPIO.BOARD )
GPIO.setup( pins, GPIO.OUT )

if GPIO.input( pinUP ) == 1:
	state = 'up'
elif GPIO.input( pinDN ) == 1:
	state = 'dn'
else:
	state = 0
	
UpDn = sys.argv[ 1 ]
if len( sys.argv ) == 1 and UpDn == 'state':
	print( state )
	exit()
	
second = float( sys.argv[ 2 ] )
if UpDn == 'up':
	GPIO.output( pinUP, ON )
	time.sleep( second )
	GPIO.output( pinUP, OFF )
else:
	GPIO.output( pinDN, ON )
	time.sleep( second )
	GPIO.output( pinDN, OFF )
