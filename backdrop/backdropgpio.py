#!/usr/bin/python

# Each set:
# 1 x limitUP - infrared sensor > input
# 1 x UP      - web page        > output
# 1 x DN      - web page        > output
# 1 x limitDN - infrared sensor > input

import RPi.GPIO as GPIO
import json
import urllib
import urllib2

ON = 1
OFF = 0

# 26 pins avilable
#pinUPlist =      [  7,  8, 10, 11, 12, None, 13 ]
#pinDNlist =      [ 15, 16, 18, 19, 21, None, 22 ]
#pinList = [ p for p in pinUPlist + pinDNlist if p is not None ]

#pinLimitUPlist = [ 23, 24, 26, 29, 31, None, 32 ] # no backdrop 6
#pinLimitDNlist = [ 33, 35, 36, 37, 38, None, 40 ]
#pinLimitList = [ p for p in pinLimitUPlist + pinLimitDNlist if p is not None ]

pinUPlist =      [  7,  8, 10, 11, 12, None, 13 ]
pinDNlist =      [ 22, 16, 18, 19, 21, None, 15 ]
pinList = [ p for p in pinUPlist + pinDNlist if p is not None ]

pinLimitUPlist = [ 23, 24, 26, 29, 31, None, 32 ]
pinLimitDNlist = [ 33, 35, 36, 37, 38, None, 40 ]
pinLimitList = [ p for p in pinLimitUPlist + pinLimitDNlist if p is not None ]

GPIO.setwarnings( 0 )
GPIO.setmode( GPIO.BOARD )
GPIO.setup( pinList, GPIO.OUT )
GPIO.setup( pinLimitList, GPIO.IN )
