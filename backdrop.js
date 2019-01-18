var tap = 0;
var manual = 0;
var backdroppy = '/srv/http/backdrop/backdrop.py ';
var backdropphp = 'backdrop.php';

// backdroplimit.py - start only if not running
$.post( backdropphp, { bash: '! /usr/bin/pgrep backdroplimit &> /dev/null && /srv/http/backdrop/backdroplimit.py &' } );

var nameW = Math.max.apply( Math, $( '.name' ).map( function() { return $( this ).width(); } ).get() );
$( '.name, .inputname' ).css( 'width', nameW + 10 );

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
	var limit = limit[ 0 ]; // limit is array
	var UpDn = limit.UpDn;
	var num = limit.num;
	var active = limit.active;
	if ( UpDn === 'up' ) {
		$( '#up'+ num ).toggleClass( 'disable', active );
	} else {
		$( '#dn'+ num ).toggleClass( 'disable', active );
	}
	setButtonOff( num )
}
pushstream.connect();

$( '.updn, .oupdn' ).click( function() {
	manual = 0;
	if ( $( this ).hasClass( 'disable' ) ) return
	
	var UpDnid = this.id.replace( 'o', '' );
	var UpDn = UpDnid.slice( 0, 2 );
	var num = UpDnid.slice( -1 );
	var pairid = ( UpDn === 'up' ? 'dn' : 'up' ) + num
	var ms = $( '#ms-'+ UpDnid ).val();
	
	var $UpDn = $( '#'+ UpDnid );
	var $oUpDn = $( '#o'+ UpDn + num );
	$pair = $( '#'+ pairid );
	$( '.manual' ).addClass( 'hide' );
	
	if ( $( this ).hasClass( 'updn' ) ) {
		if ( $pair.hasClass( 'hide' ) ) {
			setButtonOff( num );
			var command = UpDnid;
		} else {
			setButtonOn( $UpDn, $oUpDn );
			var command = UpDnid +' '+ ( ms / 1000 ) +' &> /dev/null &';
		}
	} else {
		setButtonOff( num );
		var command = UpDnid;
	}
	$.post( backdropphp, { bash: backdroppy + command } );
} );
$.event.special.tap.emitTapOnTaphold = false; // suppress tap on taphold
$( '.manual' ).on( 'touchstart mousedown', function() {
	tap = 1; // set to suppress touchend on tap
	manual = 1;
} ).on( 'tap', function() {
	var step = $( '#step' ).val();
	var UpDnid = this.id.replace( 'manual-', '' );
	$.post( backdropphp, { bash: backdroppy + UpDnid +' '+ ( step / 1000 ) +' manual &> /dev/null &' } );
} ).taphold( function( e ) {
	tap = 0; // clear to allow touchend
	var UpDnid = this.id.replace( 'manual-', '' );
	var ms = $( '#ms-'+ UpDnid ).val();
	$.post( backdropphp, { bash: backdroppy + UpDnid +' '+ ( ms / 1000 ) +' manual &> /dev/null &' } );
} ).on( 'touchend mouseup', function() {
	if ( tap ) { // suppress and reset if tap
		tap = 0;
		return
	}
	
	var UpDnid = this.id.replace( 'manual-', '' );
	$.post( backdropphp, { bash: backdroppy + UpDnid } );
} );
$( '#manual' ).click( function() {
	$( '.manual' ).toggleClass( 'hide' );
	if ( window.innerWidth < 420 ) {
		$( '.name' ).toggleClass( 'hide' );
		$( '.number' ).toggleClass( 'valign15', ( $( '.name' ).hasClass( 'hide' ) ? '15px' : '' ) );
	}
	if ( window.innerWidth < 340 ) $( '.updn' ).toggleClass( 'hide' );
} );
$( '#setting' ).click( function() {
	if ( !$( '.updn' ).hasClass( 'hide' ) ) set();
} );
$( 'input' ).click( function() {
	$( '#save' ).removeClass( 'disable' );
} );
$( '#save' ).click( function() {
	if ( $( '#save' ).hasClass( 'disable' ) ) return
	
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
function setButton() {
	$.post( backdropphp, { bash: backdroppy +'state' }, function( state ) {
		state = JSON.parse( state );
		$( '.updn' ).removeClass( 'hide' );
		$( '.oupdn' ).addClass( 'hide' );
		
		$.each( state.limitUp, function( i, num ) {
			$( '#up'+ num ).addClass( 'disable' );
		} );
		$.each( state.limitDn, function( i, num ) {
			$( '#dn'+ num ).addClass( 'disable' );
		} );
		$.each( state.up, function( i, num ) {
			$( '#up'+ num ).addClass( 'hide' );
			$( '#oup'+ num ).removeClass( 'hide' );
			$( '#manual-up'+ num +', #manual-dn'+ num ).addClass( 'disable' );
		} );
		$.each( state.dn, function( i, num ) {
			$( '#dn'+ num ).addClass( 'hide' );
			$( '#odn'+ num ).removeClass( 'hide' );
			$( '#manual-up'+ num +', #manual-dn'+ num ).addClass( 'disable' );
		} );
	} );
}
function setButtonOn( $UpDn, $oUpDn ) {
	$UpDn.addClass( 'hide' );
	$oUpDn.removeClass( 'hide' );
}
function setButtonOff( num ) {
	$( '#up'+ num +', #dn'+ num ).removeClass( 'hide' );
	$( '#oup'+ num +', #odn'+ num ).addClass( 'hide' );
}
function set() {
	$( '#title' ).html( '<br>ตั้ ง ค่ า&emsp;<i class="fa fa-sliders wh"></i>' );
	$( '.ms, .setting, .inputname, .step, .boxed-group.unused' ).removeClass( 'hide' );
	$( '.manual, .updn, #manual, #setting, .name' ).addClass( 'hide' );
	$( '.boxed-group' ).css( 'padding', '5px 0' );
	$( '.number' ).css( 'vertical-align', '-4px' );
}
function restore() {
	$( '#title' ).html( '<br>ค ว บ คุ ม&emsp;<i class="fa fa-up-down wh"></i>' );
	$( '.ms, .setting, .inputname, .step, .boxed-group.unused' ).addClass( 'hide' );
	$( '.updn, #manual, #setting, .name' ).removeClass( 'hide' );
	$( '#save' ).addClass( 'disable' );
	$( '.boxed-group' ).css( 'padding', '' );
	$( '.number' ).css( 'vertical-align', '' );
}
