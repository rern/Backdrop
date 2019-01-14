#!/usr/bin/python

# Each set:
# 1 x limitUP - timer    > disable buttons (2nd limit: hardware limit switch)
# 1 x UP      - web page > output
# 1 x DN      - web page > output
# 1 x limitDN - infrared > input OFF       (2nd limit: timer)

import RPi.GPIO as GPIO
import time
import sys
import json
import urllib
import urllib2

ON = 1
OFF = 0
increment = 0.05
url = 'http://localhost/pub?id=backdrop'
headerdata = { 'Content-type': 'application/json', 'Accept': 'application/json' }

pinUPlist = [  5,  7,  8, 23, 11, 13, 10 ]
pinDNlist = [ 15, 16, 18, 19, 21, 22, 12 ]

# 26 pins avilable
# pinUPlist = [  5,  7,  8, 10, 11, 12, 13 ]
# pinDNlist = [ 15, 16, 18, 19, 21, 22, 23 ]
pinList = pinUPlist + pinDNlist
pinLimitList = [ 32, 33, 35, 36, 37, 38, 40 ]

GPIO.setwarnings( 0 )
GPIO.setmode( GPIO.BOARD )
GPIO.setup( pinList, GPIO.OUT )
GPIO.setup( pinLimitList, GPIO.IN )
