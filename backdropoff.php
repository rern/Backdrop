<?php
exec( '/usr/bin/sudo /usr/bin/killall -9 backdrop.py &> /dev/null' );
exec( '/usr/bin/sudo /root/backdropoff.py &> /dev/null &' );
