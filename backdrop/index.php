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
	<link rel="stylesheet" href="backdrop.css">
</head>
<body>
<div class="container">
<img id="logo" src="backdrop.png">
<a id="head">ฉ า ก</a><br>
<a id="title">ค ว บ คุ ม&emsp;<i class="fa fa-up-down wh"></i></a>
<i id="setting" class="setting fa fa-gear"></i>
<i id="close" class="setting fa fa-times hide"></i><i id="save" class="setting fa fa-save hide"></i>

<?php
$redis = new Redis();
$redis->pconnect( '127.0.0.1' );
$up = $redis->hGet( 'backdrops', 'up' );
$dn = $redis->hGet( 'backdrops', 'dn' );
$name = $redis->hGet( 'backdrops', 'name' );
$increment = $redis->hGet( 'backdrops', 'increment' );
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
		<i id="manual-up'.$i.'" class="manual fa fa-arrow-up-circle"></i>
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
		<i id="manual-dn'.$i.'" class="manual fa fa-arrow-down-circle"></i>
	</div>
	';
}
echo $html.'
	<span class="increment hide">กด-ปล่อย <input id="increment" name="increment" type="text" class="increment inputname hide" value="'.$increment.'"> /ครั้ง</span>
	<span id="unit" class="increment hide">(หน่วย: 1/1000 วินาที)</span>
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
