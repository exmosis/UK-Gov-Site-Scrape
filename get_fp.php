<?php

$outfile = './wordcount.2.txt';

$sites = include_once('sites.php');
$sites = explode("\n", trim($sites));

$words = array();

$attempted = 0;
$ok = 0;

foreach ($sites as $s) {
	$s = trim($s);
	$s = preg_replace('/[^a-zA-Z0-9\.\/\?#!\[\]\(\)_\-]+$/', '', $s);
	if (strpos($s, '://') === false) {
		$s = 'http://' . $s;
	}
	echo "$s\n";
	$attempted++;
	$fp = file_get_contents($s);
	if ($fp) {
		$ok++;
		$fp = preg_replace('/\n/', ' ', $fp);
		$fp = preg_replace('/^.+<body[ >]/', '', $fp);
		$fp = preg_replace('/<script[ >].*<\/script>/', '', $fp);
		$fp = preg_replace('/<style[ >].*<\/style>/', '', $fp);
		$fp = preg_replace('/<!--.*-->/', '', $fp);
		$fp = preg_replace('/<[^>]*>/', ' ', $fp);
		$fp = preg_replace('/[^a-zA-Z0-9\']/', ' ', $fp);
		$fp = preg_replace('/\s+/', ' ', $fp);
		$fp_words = explode(' ', $fp);
		foreach ($fp_words as $w) {
			$w = preg_replace('/^\'+/', '', $w);
			$w = preg_replace('/\'+$/', '', $w);
			if (strlen($w) > 2) {
				if (! array_key_exists($w, $words)) {
					$words[$w] = 0;
				}
				$words[$w]++;
			}
		}
	}
}

echo "Sorting.\n";
ksort($words);

echo "Writing.\n";
$f = fopen($outfile, "w");
foreach ($words as $w => $wc) {
	fwrite($f, $w . ':' . $wc . "\n");
}
fclose($f);

echo "Done with $ok / $attempted sites.\n";
exit;

?>

