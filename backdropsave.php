<?php
$redis = new Redis();
$redis->pconnect( '127.0.0.1' );
foreach ( range( 1, 7 ) as $i ) {
	$up.= ','.$_POST[ 'ms-up'.$i ];
	$dn.= ','.$_POST[ 'ms-dn'.$i ];
}
$up = ltrim( $up, ',' );
$dn = ltrim( $dn, ',' );
echo $up.' - '.$dn;
$redis->hSet( 'backdrops', 'up', $up );
$redis->hSet( 'backdrops', 'dn', $dn );
