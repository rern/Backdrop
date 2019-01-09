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
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/addonsinfo.css">
	<style>
	@font-face {
		font-family: DSN_Kamon;
		src: url( 'assets/fonts/DSN_Kamon.woff' ) format( 'woff' ), url( 'assets/fonts/DSN_Kamon.ttf' ) format( 'truetype' );
		font-weight: normal;
		font-style: normal;
	}
	@font-face {
		font-family: enhance;
		src        : url( 'assets/fonts/enhance.woff' ) format( 'woff' ), url( 'assets/fonts/enhance.ttf' ) format( 'truetype' );
		font-weight: normal;
		font-style : normal;
	}
	.fa {
		font-family: enhance;
		font-style: normal;
	}
	.fa-arrow-up:before { content: "\f566" }
	.fa-arrow-down:before { content: "\f567" }
	.fa-plus-circle:before { content: "\f51D" }
	.fa-gear:before { content: "\f509" }
	.fa-save:before { content: "\f542"}
	.fa-times:before { content: "\f51A" }
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
		line-height: 64px;
		background: #2b3c4e;
	}
	#close {
		color: red;
	}
	.updn,
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
	.ms.up {
		background: rgba(0, 128, 0, .5);
	}
	.ms.dn {
		background: rgba(255, 0, 0, .5);
	}
	.updn {
		font-size: 44px;
	}
	.btn.up {
		background: green;
	}
	.btn.dn {
		background: red;
	}
	.increment,
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
	.increment {
		-webkit-touch-callout: none; /* iOS Safari */
		-webkit-user-select: none;   /* Safari */
		-moz-user-select: none;      /* Firefox */
		-ms-user-select: none;       /* Internet Explorer and Edge */
		user-select: none;           /* Non-prefixed version, currently supported by Chrome and Opera */
	}
	.label {
		display: inline-block;
		vertical-align: -15px;
		line-height: 20px;
	}
	.number {
		width: 32px;
		font-size: 32px;
		text-align: center;
	}
	.ms,
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
		opacity: 0.5;
	}
	@media(max-width: 375px) {
		.increment.disable,
		.name {
			display: none;
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
<img id="logo" src="assets/img/backdrop.png">
<a id="head">ฉ า ก</a><br>
<a id="title">ค ว บ คุ ม</a>
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
$html.='
<div class="boxed-group">
	<i id="increment-up'.$i.'" class="increment fa fa-plus-circle"></i>
	<a id="up'.$i.'" class="updn up btn"><i class="fa fa-arrow-up"></i></a>
	<input id="ms-up'.$i.'" name="ms-up'.$i.'" type="text" class="ms up hide" value="'.$up[ $i - 1 ].'">
	<div class="label">
		<div class="number">'.$i.'</div>
		<div id="name'.$i.'" class="name">'.( $name[ $i - 1 ] === '0' ? '&nbsp;' : $name[ $i - 1 ] ).'</div>
		<input id="inputname'.$i.'" name="inputname'.$i.'" type="text" class="inputname hide" value="'.( $name[ $i - 1 ] === '0' ? '' : $name[ $i - 1 ] ).'">
	</div>
	<a id="dn'.$i.'" class="updn dn btn"><i class="fa fa-arrow-down"></i></a>
	<input id="ms-dn'.$i.'" name="ms-dn'.$i.'" type="text" class="ms dn hide" value="'.$dn[ $i - 1 ].'">
	<i id="increment-dn'.$i.'" class="increment fa fa-plus-circle"></i>
</div>
';
}
echo $html.'</form>';
?>
</div>

<script src="/js/vendor/jquery-2.1.0.min.js"></script>
<script src="/js/vendor/jquery.mobile.custom.min.js"></script>
<script src="/js/addonsinfo.js"></script>
<script>
var nameW = Math.max.apply( Math, $( '.name' ).map( function() { return $( this ).width(); } ).get() );
$( '.name, .inputname' ).css( 'width', nameW + 12 );

$( '.btn' ).click( function() {
	$.post( 'enhance.php', { bash: '/root/backdropoff.py &> /dev/null' } );
	var $this = $( this );
	var updn = this.id
	var ms = $( '#ms-'+ this.id ).val();
	$( '.increment' ).addClass( 'disable' );
	if ( $( '.updn' ).hasClass( 'blink' ) ) {
		$( '.updn' ).removeClass( 'blink' );
		$( '.increment' ).removeClass( 'disable' );
	} else {
		$( '.btn' ).removeClass( 'blink' );
		$this.addClass( 'blink' );
		setTimeout( function() {
			$this.removeClass( 'blink' );
			$( '.increment' ).removeClass( 'disable' );
		}, ms );
		setTimeout( function() {
			$.post( 'enhance.php', { bash: '/root/backdrop.py '+ updn +' '+ ( ms / 1000 ) +' &> /dev/null &' } );
		}, 300 );
	}
} );
var tap = 0;
$.event.special.tap.emitTapOnTaphold = false; // suppress tap on taphold
$( '.increment' ).on( 'touchstart mousedown', function() {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	tap = 1; // set to suppress touchend on tap
} ).on( 'tap', function() {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	var ms = 100;
	var updn = this.id.replace( 'increment-', '' );
	$.post( 'enhance.php', { bash: '/root/backdrop.py '+ updn +' '+ ( ms / 1000 ) +' &> /dev/null &' } );
} ).taphold( function( e ) {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	tap = 0; // clear to allow touchend
	var updn = this.id.replace( 'increment-', '' );
	var ms = $( '#ms-'+ updn ).val();
	$.post( 'enhance.php', { bash: '/root/backdrop.py '+ updn +' '+ ( ms / 1000 ) +' &> /dev/null &' } );
} ).on( 'touchend mouseup', function() {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	if ( tap ) { // suppress and reset if tap
		tap = 0;
		return
	}
	
	$.post( 'enhance.php', { bash: '/root/backdropoff.py &> /dev/null' } );
} );
$( '#setting' ).click( function() {
	if ( $( '.updn' ).hasClass( 'blink' ) ) return
	
	set();
} );
$( '#save' ).click( function() {
	$.post( 'backdropsave.php', $( '#formms').serialize(), function() {
		info( 'Settings saved.' );
		restore();
		$.each( $( '.inputname' ), function( i, el ) {
			$( '#name'+ ( i + 1 ) ).text( $( '#inputname'+ ( i + 1 ) ).val() );
		} );
		$( '.name' ).css( 'width', '' );
		var nameW = Math.max.apply( Math, $( '.name' ).map( function() { return $( this ).width(); } ).get() );
		$( '.name, .inputname' ).css( 'width', nameW + 12 );
	} );
} );
$( '#close' ).click( restore );
function set() {
	$( '#title' ).text( 'ตั้ ง ค่ า' );
	$( '.ms, .setting, .inputname' ).removeClass( 'hide' );
	$( '.updn, #setting, .name' ).addClass( 'hide' );
	$( '.increment' ).addClass( 'disable' );
	$( '.ms' ).css( 'vertical-align', '8px' );
	$( '.number' ).css( 'vertical-align', '0' );
}
function restore() {
	$( '#title' ).text( 'ค ว บ คุ ม' );
	$( '.ms, .setting, .inputname' ).addClass( 'hide' );
	$( '.updn, #setting, .name' ).removeClass( 'hide' );
	$( '.increment' ).removeClass( 'disable' );
	$( '.ms' ).css( 'vertical-align', '' );
	$( '.number' ).css( 'vertical-align', '' );
}
</script>
</body>
</html>
