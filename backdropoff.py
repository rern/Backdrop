#!/usr/bin/python
import RPi.GPIO as GPIO
import os

os.system( '/usr/bin/sudo /usr/bin/killall -9 backdrop.py &> /dev/null' )

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
#GPIO.setup( pinLimits, GPIO.IN )

GPIO.output( pins, OFF )
