#!/usr/bin/python

# Auto, large buttons, always OFF at limits
# Maual, small buttons, can keep ON pass limits
# Both auto and manual have OFF timers in case limits not detected

from backdropgpio import *

arg1 = sys.argv[ 1 ]

if arg1 == 'set':
	GPIO.output( pinList, OFF )
	exit()
	
if arg1 == 'state':
	upList = []
	dnList = []
	limitUpList = []
	limitDnList = []
	for i in range( 0, 7 ):
		pin = pinUpList[ i ]
		if pin and GPIO.input( pin ) == ON:
			upList.append( i + 1 )
		pin = pinDnList[ i ]
		if pin and GPIO.input( pin ) == ON:
			dnList.append( i + 1 )
		pin = pinUpLimitList[ i ]
		if pin and GPIO.input( pin ) == ON:
			limitUpList.append( i + 1 )
		pin = pinDnLimitList[ i ]
		if pin and GPIO.input( pin ) == ON:
			limitDnList.append( i + 1 )

	print( json.dumps( { 'up': upList, 'dn': dnList, 'limitUp': limitUpList, 'limitDn': limitDnList } ) )
	exit()

UpDn = arg1[ :2 ]
i = int( arg1[ -1: ] ) - 1

if UpDn == 'up':
	pin = pinUpList[ i ]
else:
	pin = pinDnList[ i ]

if len( sys.argv ) == 2:
	GPIO.output( pin, OFF )
	exit()
	
# prevent auto if limit already reach
if UpDn == 'up' and GPIO.input( pinUpLimitList[ i ] ) == ON and len( sys.argv ) == 3:
	exit()
if UpDn == 'dn' and GPIO.input( pinDnLimitList[ i ] ) == ON and len( sys.argv ) == 3:
	exit()
	
second = float( sys.argv[ 2 ] )

GPIO.output( pin, ON )
time.sleep( second )
GPIO.output( pin, OFF )

exit()
# for testing
data = { 'UpDn': UpDn, 'num': i + 1, 'active': 1 }
req = urllib2.Request( url, json.dumps( data ), headers = headerdata )
response = urllib2.urlopen( req )
