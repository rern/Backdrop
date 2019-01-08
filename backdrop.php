<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>BACKDROP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-tap-highlight" content="no">
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/addonsinfo.css">
	<style>
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
		.container {
			height: 100vh;
		}
		.boxed-group {
			margin-bottom: 5px;
			padding: 10px 10px 3px 10px;
			border-radius: 6px;
			text-align: center;
			line-height: 75px;
			background: #2b3c4e;
		}
		.updn,
		.ms {
			display: inline-block;
			width: 76px;
			height: 76px;
			margin: 0 5px;
			padding: 0;
			border-radius: 50%;
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
			font-size: 50px;
		}
		.btn.up {
			background: green;
		}
		.btn.dn {
			background: red;
		}
		#setting,
		#save {
			float: right;
			margin-top: -15px;
			font-size: 32px;
		}
		.increment,
		.number,
		.setting {
			display: inline-block;
			width: 50px;
			font-size: 40px;
			text-align: center;
		}
		.increment {
			-webkit-touch-callout: none; /* iOS Safari */
			-webkit-user-select: none;   /* Safari */
			-moz-user-select: none;      /* Firefox */
			-ms-user-select: none;       /* Internet Explorer and Edge */
			user-select: none;           /* Non-prefixed version, currently supported by Chrome and Opera */
		}
		.number {
			display: inline-block;
			width: 32px;
			font-size: 32px;
			text-align: center;
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
	</style>
</head>
<body>
<div class="container">
<h1>BACKDROP</h1>
<legend><a id="head">Controls</a><i id="setting" class="fa fa-gear"></i><i id="save" class="fa fa-save hide"></i></legend>

<?php
$redis = new Redis();
$redis->pconnect( '127.0.0.1' );
$up = $redis->hGet( 'backdrops', 'up' );
$dn = $redis->hGet( 'backdrops', 'dn' );
$up = explode( ',', $up );
$dn = explode( ',', $dn );
$html = '<form id="formms">';
foreach ( range( 7, 1 ) as $i ) {
$html.='
<div class="boxed-group">
	<i id="increment-up'.$i.'" class="increment fa fa-plus-circle"></i>
	<a id="up'.$i.'" class="updn up btn"><i class="fa fa-arrow-up"></i></a>
	<input id="ms-up'.$i.'" name="ms-up'.$i.'" type="text" class="ms up form-control input-lg hide" value="'.$up[ $i - 1 ].'">
	<span class="number">'.$i.'</span>
	<a id="dn'.$i.'" class="updn dn btn"><i class="fa fa-arrow-down"></i></a>
	<input id="ms-dn'.$i.'" name="ms-dn'.$i.'" type="text" class="ms dn form-control input-lg hide" value="'.$dn[ $i - 1 ].'">
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
	
	tap = 1;
} ).on( 'tap', function() {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	var ms = 100;
	var updn = this.id === 'increment-up' ? 'up' : 'dn';
	$.post( 'enhance.php', { bash: '/root/backdrop.py '+ updn +' '+ ( ms / 1000 ) +' &> /dev/null &' } );
} ).taphold( function( e ) {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	tap = 0;
	var ms = this.id === 'increment-up' ? $( '#ms-up' ).val() : $( '#ms-dn' ).val();
	var updn = this.id === 'increment-up' ? 'up' : 'dn';
	$.post( 'enhance.php', { bash: '/root/backdrop.py '+ updn +' '+ ( ms / 1000 ) +' &> /dev/null &' } );
} ).on( 'touchend mouseup', function() {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	if ( tap ) {
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
	$.post( 'backdropsave.php', $( '#formms').serialize(), function(data) {
		console.log(data)
		info( 'Settings saved.' );
		restore();
	} );
} );
$( '.container' ).click( function( e ) {
	if ( !$( e.target ).hasClass( 'ms' ) && e.target.id !== 'setting' && !$( '.updn' ).hasClass( 'blink' ) ) restore();
} );
function set() {
	$( '#head' ).text( 'Duration Setting' );
	$( '.ms, #save' ).removeClass( 'hide' );
	$( '.updn, #setting' ).addClass( 'hide' );
	$( '.increment' ).addClass( 'disable' );
	$( '.ms' ).css( 'vertical-align', '10px' );
}
function restore() {
	$( '#head' ).text( 'Controls' );
	$( '.ms, #save' ).addClass( 'hide' );
	$( '.updn, #setting' ).removeClass( 'hide' );
	$( '.increment' ).removeClass( 'disable' );
	$( '.ms' ).css( 'vertical-align', '' );
}
</script>
</body>
</html>
