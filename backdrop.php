<?php
if ( isset( $_POST[ 'bash' ] ) ) {
	echo shell_exec( '/usr/bin/sudo '.$_POST[ 'bash' ] );
	exit();
}

$redis = new Redis();
$redis->pconnect( '127.0.0.1' );
foreach ( range( 1, 7 ) as $i ) {
	$up.=   ','.( $_POST[ 'ms-up'.$i ] ?: 0 );
	$dn.=   ','.( $_POST[ 'ms-dn'.$i ] ?: 0 );
	$name.= ','.( $_POST[ 'inputname'.$i ] ?: 0 );
}
$redis->hmSet(
	'backdrop', array( 
		  'up'        => ltrim( $up, ',' )
		, 'dn'        => ltrim( $dn, ',' )
		, 'name'      => ltrim( $name, ',' )
		, 'step' => $_POST[ 'step' ]
	)
);
