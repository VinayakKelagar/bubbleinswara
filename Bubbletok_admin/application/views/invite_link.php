<!DOCTYPE html>
<html lang="en-us" class="no-js">
<head>
</head>

<body>
<?php if($isAndroidOS) {

	?>
<!-- <a href="joy://app/<?php echo $referral_code;?>#Intent;scheme=market;action=android.intent.action.VIEW;package=com.joywallet;end" id="venderProfile"></a> -->

<a href="intent:#Intent;scheme=joy://app?referral=<?php echo $referral_code;?>;package=com.joywallet;end" id="venderProfile">link</a>
<?php } else { ?>
<a href="itms-apps://itunes.apple.com/us/app/imdb-movies-tv/id342792525" id="venderProfile"></a>
<?php } ?>
<script>
document.getElementById("venderProfile").click();
</script>
</body>

</html>