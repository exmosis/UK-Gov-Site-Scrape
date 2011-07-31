<?php

$count = file_get_contents('wordcount.2.txt');

$words = array();

$ignore = array(
	'nbsp',
	'and',
	'the',
	'of',
	'a',
	'amp',
	
	'home',
	'page',
	'contact',
	'information',
	'site',
	'news',
	'search',
	'website',
	'links',
	'skip'

);


$counts = explode("\n", $count);
foreach ($counts as $c) {
	$colon = strrpos($c, ':');
	$word = substr($c, 0, $colon);
	$word = strtolower($word);
	$wc = substr($c, $colon + 1);
	if (! preg_match('/[a-z]/', $word) || $wc < 3 || in_array($word, $ignore) ) {
		continue;
	}
	if (! array_key_exists($word, $words)) {
		$words[$word] = $wc;
	} else {
		$words[$word] += $wc;
	}
}

arsort($words);

foreach ($words as $word => $wc) {
	echo $word . ':' . $wc . "\n";	
}

?>
