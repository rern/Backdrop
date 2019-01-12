<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Backdrops</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-tap-highlight" content="no">
	<link rel="stylesheet" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/css/addonsinfo.css">
	<style>
	@font-face {
		font-family: DSN_Kamon;
		src: url( '/assets/fonts/DSN_Kamon.woff' ) format( 'woff' ), url( '/assets/fonts/DSN_Kamon.ttf' ) format( 'truetype' );
		font-weight: normal;
		font-style: normal;
	}
	@font-face {
		font-family: enhance;
		src        : url( '/assets/fonts/enhance.woff' ) format( 'woff' ), url( '/assets/fonts/enhance.ttf' ) format( 'truetype' );
		font-weight: normal;
		font-style : normal;
	}
	.fa {
		font-family: enhance;
		font-style: normal;
	}
	.fa-arrow-up-circle:before { content: "\f56B" }
	.fa-arrow-down-circle:before { content: "\f56C" }
	.fa-plus-circle:before { content: "\f51D" }
	.fa-sliders:before { content: "\f50B" }
	.fa-gear:before { content: "\f509" }
	.fa-save:before { content: "\f542"}
	.fa-times:before { content: "\f51A" }
	.fa-up-down:before { content: "\f568" }
	body {
		padding-top: 10px;
		font-family: 'DSN_Kamon';
	}
	.container {
		height: 100vh;
		max-width: 640px;
	}
	#logo {
		float: left;
		margin: 0 20px 0 5px;
		width: 77px;
	}
	legend {
		margin-bottom: 10px;
		line-height: 30px;
		border: none;
	}
	#head {
		font-size: 32px;
		line-height: 50px;
		color: #e0e7ee;
	}
	#title {
		font-size: 20px;
		line-height: 14px;
	}
	#formms {
		margin-top: 20px;
	}
	.boxed-group {
		margin-bottom: 5px;
		padding: 5px 10px 0 10px;
		border-radius: 6px;
		text-align: center;
		line-height: 70px;
		background: #19232d;
	}
	#close {
		color: red;
	}
	.ms {
		display: inline-block;
		width: 70px;
		height: 70px;
		margin: 0 5px;
		padding: 0;
		border-radius: 50%;
		border: none;
		vertical-align: 0;
		text-align: center;
		color: #e0e7ee;
	}
	.updn,
	.oupdn {
		display: inline-block;
		width: 70px;
		margin: 0 5px;
		vertical-align: -10px;
		font-size: 64px;
	}
	.up {
		color: #0e800e;
	}
	.dn {
		color: #ae4131;
	}
	.oup {
		color: #00ff00;
	}
	.odn {
		color: #ff0000;
	}
	.msup {
		color: ##e0e7ee;
		background: rgba(7, 97, 7, .5);
	}
	.msdn {
		color: ##e0e7ee;
		background: rgba(174, 65, 49, .7);
	}
	.manual,
	.number,
	.setting {
		display: inline-block;
		width: 50px;
		font-size: 40px;
		text-align: center;
	}
	.setting {
		float: right;
		margin-top: -25px;
	}
	.manual {
		vertical-align: -3px;
		-webkit-touch-callout: none; /* iOS Safari */
		-webkit-user-select: none;   /* Safari */
		-moz-user-select: none;      /* Firefox */
		-ms-user-select: none;       /* Internet Explorer and Edge */
		user-select: none;           /* Non-prefixed version, currently supported by Chrome and Opera */
	}
	.label {
		display: inline-block;
		vertical-align: -12px;
		line-height: 20px;
	}
	.number {
		width: 32px;
		font-size: 32px;
		text-align: center;
	}
	.ms {
		vertical-align: 4px;
		font-size: 18px;
	}
	.name,
	.inputname {
		font-size: 16px;
		text-align: center;
	}
	.inputname {
		display: block;
		height: 28px;
		padding: 0 3px;
		border-radius: 4px;
		border: none;
		color: #2b3c4e;
		background: #e0e7ee;
	}
	@keyframes blinkdot {
		50% { opacity: 0.5 }
	}
	.blink {
		animation: 1s blinkdot infinite;
		-webkit-animation: 1s blinkdot infinite;
	}
	.disable {
		opacity: 0.3;
	}
	.wh {
		color: #e0e7ee;
	}
	@media(max-width: 375px) {
		.name {
			display: none;
		}
		.label {
			vertical-align: -18px;
		}
		.number {
			vertical-align: 20px;
		}
	}
	@media(min-height: 730px) {
		.boxed-group {
			margin-bottom: 10px;
		}
	}
	</style>
</head>
<body>
<div class="container">
<img id="logo" src="backdrop.png">
<a id="head">ฉ า ก</a><br>
<a id="title">ค ว บ คุ ม&emsp;<i class="fa fa-up-down wh"></i></a>
<i id="setting" class="setting fa fa-gear"></i><i id="close" class="setting fa fa-times hide"></i><i id="save" class="setting fa fa-save hide"></i>

<?php
$redis = new Redis();
$redis->pconnect( '127.0.0.1' );
$up = $redis->hGet( 'backdrops', 'up' );
$dn = $redis->hGet( 'backdrops', 'dn' );
$name = $redis->hGet( 'backdrops', 'name' );
$up = explode( ',', $up );
$dn = explode( ',', $dn );
$name = explode( ',', $name );
$html = '<form id="formms">';
foreach ( range( 7, 1 ) as $i ) {
	$unused = $name[ $i - 1 ] ? '' : ' hide unused';
	$html.='
	<div class="boxed-group'.$unused.'">
		<i id="manual-up'.$i.'" class="manual fa fa-arrow-up-circle"></i>
		<i id="up'.$i.'" class="updn up fa fa-arrow-up-circle"></i>
		<i id="oup'.$i.'" class="oupdn oup fa fa-arrow-up-circle blink hide"></i>
		<input id="ms-up'.$i.'" name="ms-up'.$i.'" type="text" class="ms msup hide" value="'.$up[ $i - 1 ].'">
		<div class="label">
			<div class="number">'.$i.'</div>
			<div id="name'.$i.'" class="name">'.( $name[ $i - 1 ] == '0' ? '&nbsp;' : $name[ $i - 1 ] ).'</div>
			<input id="inputname'.$i.'" name="inputname'.$i.'" type="text" class="inputname hide" value="'.( $name[ $i - 1 ] == '0' ? '' : $name[ $i - 1 ] ).'">
		</div>
		<i id="dn'.$i.'" class="updn dn fa fa-arrow-down-circle"></i>
		<i id="odn'.$i.'" class="oupdn odn fa fa-arrow-down-circle blink hide"></i>
		<input id="ms-dn'.$i.'" name="ms-dn'.$i.'" type="text" class="ms msdn hide" value="'.$dn[ $i - 1 ].'">
		<i id="manual-dn'.$i.'" class="manual fa fa-arrow-down-circle"></i>
	</div>
	';
}
echo $html.'</form>';
?>
</div>

<script src="/js/vendor/jquery-2.1.0.min.js"></script>
<script src="/js/vendor/jquery.mobile.custom.min.js"></script>
<script src="/js/vendor/pushstream.min.js"></script>
<script src="/js/addonsinfo.js"></script>
<script>
var timeout;
var tap = 0;
var manual = 0;
var backdroppy = '/srv/http/backdrop/backdrop.py ';
var backdropphp = 'backdrop.php';

var nameW = Math.max.apply( Math, $( '.name' ).map( function() { return $( this ).width(); } ).get() );
$( '.name, .inputname' ).css( 'width', nameW + 10 );

function setButton() {
	$.post( backdropphp, { bash: backdroppy +'state' }, function( state ) {
		var state = JSON.parse( state );
		$( '.updn' ).removeClass( 'hide' );
		$( '.oupdn' ).addClass( 'hide' );
		$( '.manual, .updn' ).removeClass( 'disable' );
		if ( !state.on.length && !state.limitActive.length ) return
		
		$.each( state.limitActive, function( i, updnid ) {
			$( '#'+ updnid ).addClass( 'disable' );
		} );
		$.each( state.on, function( i, updnid ) {
			$( '#'+ updnid ).addClass( 'hide' );
			$( '#o'+ updnid ).removeClass( 'hide' );
			var num = updnid.slice( -1 );
			$( '#manual-up'+ num +', #manual-dn'+ num ).addClass( 'disable' );
		} );
	} );
}
setButton();
document.addEventListener( 'visibilitychange', function() {
	if ( !document.hidden ) setButton();
} );

var pushstream = new PushStream( {
	host: window.location.hostname,
	port: window.location.port,
	modes: 'websocket'
} );
pushstream.addChannel( 'backdrop' );
pushstream.onmessage = function( limit ) {
	console.log(limit)
	if ( manual === 1 || $( '#setting' ).hasClass( 'hide' ) ) return
	
	var limit = limit[ 0 ]; // limit is array
	var updnid = limit.pin;
	var updn = updnid.slice( 0, 2 );
	var num = updnid.slice( -1 );
	var pairid = ( updn === 'up' ? 'dn' : 'up' ) + num
	
	$( '#'+ updnid ).toggleClass( 'disable', limit.active );
	$( '#'+ pairid ).toggleClass( 'disable', !limit.active );
	setButtonOff( num )
}
pushstream.connect();

$( '.updn, .oupdn' ).click( function() {
	manual = 0;
	if ( $( this ).hasClass( 'disable' ) ) return
	
	var updnid = this.id.replace( 'o', '' );
	var updn = updnid.slice( 0, 2 );
	var num = updnid.slice( -1 );
	var pairid = ( updn === 'up' ? 'dn' : 'up' ) + num
	var ms = $( '#ms-'+ updnid ).val();
	
	var $updn = $( '#'+ updnid );
	var $pair = $( '#'+ pairid );
	var $oupdn = $( '#o'+ updn + num );
	var $manual = $( '#manual-up'+ num +', #manual-dn'+ num );
	
	clearTimeout( timeout );
	$manual.removeClass( 'disable' );
	
	if ( $( this ).hasClass( 'updn' ) ) {
		if ( $pair.hasClass( 'hide' ) ) {
			setButtonOff( num );
			var command = updnid;
		} else {
			setButtonOn( $updn, $oupdn, $manual );
			var command = updnid +' '+ ( ms / 1000 ) +' &> /dev/null &';
		}
	} else {
		setButtonOff( num );
		var command = updnid;
	}
	$.post( backdropphp, { bash: backdroppy + command } );
} );
function setButtonOn( $updn, $oupdn, $manual ) {
	$updn.addClass( 'hide' );
	$oupdn.removeClass( 'hide' );
	$manual.addClass( 'disable' );
}
function setButtonOff( num ) {
	$( '#up'+ num +', #dn'+ num ).removeClass( 'hide' );
	$( '#oup'+ num +', #odn'+ num ).addClass( 'hide' );
	$( '#manual-up'+ num +', #manual-dn'+ num ).removeClass( 'disable' );
}
$.event.special.tap.emitTapOnTaphold = false; // suppress tap on taphold
$( '.manual' ).on( 'touchstart mousedown', function() {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	tap = 1; // set to suppress touchend on tap
	manual = 1;
} ).on( 'tap', function() {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	var ms = 100;
	var updnid = this.id.replace( 'manual-', '' );
	$.post( backdropphp, { bash: backdroppy + updnid +' '+ ( ms / 1000 ) +' &> /dev/null &' } );
} ).taphold( function( e ) {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	tap = 0; // clear to allow touchend
	var updnid = this.id.replace( 'manual-', '' );
	var ms = $( '#ms-'+ updnid ).val();
	$.post( backdropphp, { bash: backdroppy + updnid +' '+ ( ms / 1000 ) +' &> /dev/null &' } );
} ).on( 'touchend mouseup', function() {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	if ( tap ) { // suppress and reset if tap
		tap = 0;
		return
	}
	
	var updnid = this.id.replace( 'manual-', '' );
	$.post( backdropphp, { bash: backdroppy + updnid } );
} );
$( '#setting' ).click( function() {
	if ( !$( '.updn' ).hasClass( 'hide' ) ) set();
} );
$( '#save' ).click( function() {
	$.post( backdropphp, $( '#formms').serialize(), function() {
		info( 'Settings saved.' );
		$.each( $( '.inputname' ), function( i, el ) {
			var val = $( '#inputname'+ ( i + 1 ) ).val();
			$( '#name'+ ( i + 1 ) ).html( val ? val : '&nbsp;' );
			$( '#inputname'+ ( i + 1 ) ).parent().parent().toggleClass( 'unused', !val );
		} );
		restore();
		$( '.name' ).css( 'width', '' );
		var nameW = Math.max.apply( Math, $( '.name' ).map( function() { return $( this ).width(); } ).get() );
		$( '.name, .inputname' ).css( 'width', nameW + 12 );
	} );
} );
$( '#close' ).click( restore );
function set() {
	$( '#title' ).html( 'ตั้ ง ค่ า&emsp;<i class="fa fa-sliders wh"></i>' );
	$( '.ms, .setting, .inputname, .boxed-group.unused' ).removeClass( 'hide' );
	$( '.manual, .updn, #setting, .name' ).addClass( 'hide' );
	$( '.number' ).css( 'vertical-align', 0 );
}
function restore() {
	$( '#title' ).html( 'ค ว บ คุ ม&emsp;<i class="fa fa-up-down wh"></i>' );
	$( '.ms, .setting, .inputname, .boxed-group.unused' ).addClass( 'hide' );
	$( '.manual, .updn, #setting, .name' ).removeClass( 'hide' );
	$( '.number' ).css( 'vertical-align', '' );
}
</script>
</body>
</html>
