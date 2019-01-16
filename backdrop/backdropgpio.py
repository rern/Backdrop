#!/usr/bin/python

# Each set:
# 1 x limitUP - infrared > disable buttons (2nd limit: timer)
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
step = 0.05
url = 'http://localhost/pub?id=backdrop'
headerdata = { 'Content-type': 'application/json', 'Accept': 'application/json' }

pinUpList = [ 12, 13, 15, None, None, None, 10 ]
pinDnList = [ 18, 19, 21, None, None, None,  8 ]

# 26 pins avilable
# pinUpList = [ 12, 13, 15, None, None, None, 16 ]
# pinDnList = [ 18, 19, 21, None, None, None, 22 ]
pinList = [ p for p in pinUpList + pinDnList if p is not None ]
pinUpLimitList = [ 23,32, 33, None, None, None, 35 ]
pinDnLimitList = [ 36, 37, 38, None, None, None, 40 ]
pinLimitList = [ p for p in pinUpLimitList + pinDnLimitList if p is not None ]

GPIO.setwarnings( 0 )
GPIO.setmode( GPIO.BOARD )
GPIO.setup( pinList, GPIO.OUT )
GPIO.setup( pinLimitList, GPIO.IN )
