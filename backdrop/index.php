<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Backdrop</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-tap-highlight" content="no">
	<link rel="stylesheet" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/css/addonsinfo.css">
	<link rel="stylesheet" href="backdrop.css">
</head>
<body>
<div class="container">
<img id="logo" src="backdrop.png">
<a id="head">ฉ า ก</a>
<a id="title"><br>ค ว บ คุ ม&emsp;<i class="fa fa-up-down wh"></i></a>
<i id="setting" class="setting fa fa-gear"></i><i id="manual" class="setting fa fa-manual"></i>
<i id="close" class="setting fa fa-times hide"></i><i id="save" class="setting fa fa-save hide"></i>

<?php
$redis = new Redis();
$redis->pconnect( '127.0.0.1' );
if ( !$redis->exists( 'backdrop' ) ) {
	$redis->hSet( 'backdrop', 'up', '3000,3000,3000,3000,3000,3000,3000' );
	$redis->hSet( 'backdrop', 'dn', '3000,3000,3000,3000,3000,3000,3000' );
	$redis->hSet( 'backdrop', 'name', '1,2,3,4,5,6,7' );
	$redis->hSet( 'backdrop', 'step', '500' );
}
$up = $redis->hGet( 'backdrop', 'up' );
$dn = $redis->hGet( 'backdrop', 'dn' );
$name = $redis->hGet( 'backdrop', 'name' );
$step = $redis->hGet( 'backdrop', 'step' );
$up = explode( ',', $up );
$dn = explode( ',', $dn );
$name = explode( ',', $name );
$html = '
<form id="formms">
';
foreach ( range( 7, 1 ) as $i ) {
	$unused = $name[ $i - 1 ] ? '' : ' hide unused';
	$html.='
	<div class="boxed-group'.$unused.'">
		<i id="manual-up'.$i.'" class="manual fa fa-arrow-up-circle hide"></i>
		<i id="up'.$i.'" class="updn up fa fa-arrow-up-circle disable"></i>
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
		<i id="manual-dn'.$i.'" class="manual fa fa-arrow-down-circle hide"></i>
	</div>
	';
}
echo $html.'
	<span class="step hide">Manual step <input id="step" name="step" type="text" class="step inputname hide" value="'.$step.'"></span>
	<span id="unit" class="step hide">(unit: 1/1000 second)</span>
</form>
';
?>
</div>

<script src="/js/vendor/jquery-2.1.0.min.js"></script>
<script src="/js/vendor/jquery.mobile.custom.min.js"></script>
<script src="/js/vendor/pushstream.min.js"></script>
<script src="/js/addonsinfo.js"></script>
<script src="backdrop.js"></script>
</body>
</html>
