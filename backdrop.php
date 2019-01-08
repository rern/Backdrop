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
			padding: 10px 10px 3px 10px;
			border-radius: 6px;
			line-height: 75px;
			background: #2b3c4e;
		}
		.updn,
		.ms {
			display: inline-block;
			width: 80px;
			height: 80px;
			margin: 0 5px;
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
			padding: 0;
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
<?php
$redis = new Redis();
$redis->pconnect( '127.0.0.1' );
$up = $redis->hGet( 'backdrop', 'up' );
$dn = $redis->hGet( 'backdrop', 'dn' );
?>
<body>
<div class="container">
<h1>BACKDROP</h1>
<legend><a id="head">Controls</a><i id="setting" class="fa fa-gear"></i><i id="save" class="fa fa-save hide"></i></legend>
<div class="boxed-group">
	<i id="increment-up" class="increment fa fa-plus-circle"></i>
	<a id="up" class="updn up btn"><i class="fa fa-arrow-up"></i></a>
	<input id="ms-up" type="text" class="ms up form-control input-lg hide" value="<?=$up?>">
	<span class="number">7</span>
	<a id="dn" class="updn dn btn"><i class="fa fa-arrow-down"></i></a>
	<input id="ms-dn" type="text" class="ms dn form-control input-lg hide" value="<?=$dn?>">
	<i id="increment-dn" class="increment fa fa-plus-circle"></i>
</div>
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
	if ( $this.hasClass( 'blink' ) ) {
		$this.removeClass( 'blink' );
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
var msup = $( '#ms-up' ).val();
var msdn = $( '#ms-dn' ).val();
$( '#setting' ).click( function() {
	msup = $( '#ms-up' ).val();
	msdn = $( '#ms-dn' ).val();
	set();
} );
$( '#save' ).click( function() {
	var up = $( '#ms-up' ).val();
	var dn = $( '#ms-dn' ).val();
	$.post( 'enhance.php', { bash: '/usr/bin/redis-cli hmset backdrop up '+ up +' dn '+ dn }, function() {
		info( 'Settings saved.' );
		restore();
	} );
} );
$( '.container' ).click( function( e ) {
	if ( !$( e.target ).hasClass( 'ms' ) && e.target.id !== 'setting' ) restore();
} );
function set() {
	$( '#head' ).text( 'Duration Setting' );
	$( '.ms, #save' ).removeClass( 'hide' );
	$( '.updn, #setting' ).addClass( 'hide' );
	$( '.increment' ).addClass( 'disable' );
	$( '.ms' ).css( 'vertical-align', '8px' );
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
