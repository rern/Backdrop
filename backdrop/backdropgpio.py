#!/usr/bin/python
import RPi.GPIO as GPIO
import json
import urllib
import urllib2

ON = 1
OFF = 0

# 26 pins avilable
#pinLimitUPlist = [ 23, 24, 26, 29, 31, None, 32 ] # no backdrop 6
#pinLimitDNlist = [ 33, 35, 36, 37, 38, None, 40 ]
#pinLimitList = [ p for p in pinLimitUPlist + pinLimitDNlist if p is not None ]

#pinUPlist =      [  3,  5,  7,  8, 10, 11, 12 ]
#pinDNlist =      [ 13, 15, 16, 18, 19, 21, 22 ]
#pinList = [ p for p in pinUPlist + pinDNlist if p is not None ]

pinLimitUPlist = [ 23, 24, 26, 29, 31, None, 32 ]
pinLimitDNlist = [ 33, 35, 36, 37, 38, None, 40 ]
pinLimitList = [ p for p in pinLimitUPlist + pinLimitDNlist if p is not None ]

pinUPlist =      [  3,  5,  7,  8, 10, 12, 11 ]
pinDNlist =      [ 13, 22, 16, 18, 19, 21, 15 ]
pinList = [ p for p in pinUPlist + pinDNlist if p is not None ]

GPIO.setwarnings( 0 )
GPIO.setmode( GPIO.BOARD )
GPIO.setup( pinList, GPIO.OUT )
GPIO.setup( pinLimitList, GPIO.IN )
