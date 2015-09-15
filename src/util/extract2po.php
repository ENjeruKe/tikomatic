<?php
require_once __DIR__.'/../../vendor/autoload.php';
use Symfony\Component\Finder\Finder;

$path = $argv[1];

//echo "Look in $path\n";

$finder = new Finder();
$finder->files()->in( $path )->name('*.php');

$poArray = [];

foreach ($finder as $file) {
	$fileStrArray = [];
	//echo "Parsing " . $file->getRelativePathname() . "...\n";
	$results = parseForTrans($file->getRealpath());

	foreach( $results as $result ) {
		$fileStrArray[$result]['references'][] = $file->getRelativePathname();
	}

    $poArray = array_merge($poArray, $fileStrArray);
}


echo formattedForPo( $poArray );
exit;


function formattedForPo( $array ) {
	global $argv;
	$poStr = "# LANGUAGE translation for Tikomatic\n";
	$poStr .= "# Generated ".date('c')." using extract2po.php\n";
	$poStr .= "# php " . $argv[0] . " " . $argv[1] . "\n";
	$poStr .= "\n\n";
	foreach ($array as $str=>$entry) {
		if ( array_key_exists('references', $entry )) {
			$poStr .= "# Referenced in " . implode("\n#", $entry['references']). "\n";
		}
		$poStr .= "msgid \"$str\"\n";
		$poStr .= "msgstr \"$str\"\n\n";
	}
	return $poStr;

}
function parseForTrans( $filename ) {
	if (file_exists( $filename ) ) {
		$contents = file_get_contents($filename);
		preg_match_all('/\->trans\(\s?\"?\'?([a-zA-Z0-9 ]+)\'?\"?\)/', $contents, $matches );
		return $matches[1];
	} else {
		return false;
	}

}