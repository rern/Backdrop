var increment = '<?=$increment?>';
var timeout;
var tap = 0;
var manual = 0;
var backdroppy = '/srv/http/backdrop/backdrop.py ';
var backdropphp = 'backdrop.php';

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
	if ( manual === 1 || $( '#setting' ).hasClass( 'hide' ) ) return
	
	var limit = limit[ 0 ]; // limit is array
	var updn = limit.updn;
	var num = limit.num;
	var updnid = updn + num
	var pairid = ( updn === 'up' ? 'dn' : 'up' ) + num
	
	$( '#'+ updnid ).addClass( 'disable' );
	$( '#'+ pairid ).removeClass( 'disable' );
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
$.event.special.tap.emitTapOnTaphold = false; // suppress tap on taphold
$( '.manual' ).on( 'touchstart mousedown', function() {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	tap = 1; // set to suppress touchend on tap
	manual = 1;
} ).on( 'tap', function() {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	var updnid = this.id.replace( 'manual-', '' );
	$.post( backdropphp, { bash: backdroppy + updnid +' '+ ( increment / 1000 ) +' manual &> /dev/null &' } );
} ).taphold( function( e ) {
	if ( $( this ).hasClass( 'disable' ) ) return
	
	tap = 0; // clear to allow touchend
	var updnid = this.id.replace( 'manual-', '' );
	var ms = $( '#ms-'+ updnid ).val();
	$.post( backdropphp, { bash: backdroppy + updnid +' '+ ( ms / 1000 ) +' manual &> /dev/null &' } );
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
function setButton() {
	$.post( backdropphp, { bash: backdroppy +'state' }, function( state ) {
		var state = JSON.parse( state );
		$( '.updn' ).removeClass( 'hide' );
		$( '.oupdn' ).addClass( 'hide' );
		$( '.manual, .dn' ).removeClass( 'disable' );
		$( '.up' ).addClass( 'disable' );
		if ( !state.on.length && !state.limitActive.length ) return
		
		$.each( state.limitActive, function( i, num ) {
			$( '#up'+ num ).removeClass( 'disable' );
			$( '#dn'+ num ).addClass( 'disable' );
		} );
		$.each( state.on, function( i, updnid ) {
			$( '#'+ updnid ).addClass( 'hide' );
			$( '#o'+ updnid ).removeClass( 'hide' );
			var num = updnid.slice( -1 );
			$( '#manual-up'+ num +', #manual-dn'+ num ).addClass( 'disable' );
		} );
	} );
}
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
function set() {
	$( '#title' ).html( 'ตั้ ง ค่ า&emsp;<i class="fa fa-sliders wh"></i>' );
	$( '.ms, .setting, .inputname, .increment, .boxed-group.unused' ).removeClass( 'hide' );
	$( '.manual, .updn, #setting, .name' ).addClass( 'hide' );
	$( '.number' ).css( 'vertical-align', 0 );
}
function restore() {
	$( '#title' ).html( 'ค ว บ คุ ม&emsp;<i class="fa fa-up-down wh"></i>' );
	$( '.ms, .setting, .inputname, .increment, .boxed-group.unused' ).addClass( 'hide' );
	$( '.manual, .updn, #setting, .name' ).removeClass( 'hide' );
	$( '.number' ).css( 'vertical-align', '' );
}
