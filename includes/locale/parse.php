<?php

// http://zagru.ru/includes/locale/parse.php?type=theme&name=admin
// http://zagru.ru/includes/locale/parse.php?type=plugin&name=auth&action=update&lang=ru_RU

function array_push_array(&$arr) {
    $args = func_get_args();
    array_shift($args);

    if (!is_array($arr)) {
        trigger_error(sprintf("%s: Cannot perform push on something that isn't an array!", __FUNCTION__), E_USER_WARNING);
        return false;
    }

    foreach($args as $v) {
        if (is_array($v)) {
            if (count($v) > 0) {
                array_unshift($v, &$arr);
                call_user_func_array('array_push',  $v);
            }
        } else {
            $arr[] = $v;
        }
    }
    return count($arr);
}

if ($_GET['type'] == 'theme') {
	$dir = '../../themes/'.$_GET['name'];
	$files = glob($dir.'/'."*.twig");
	//$pattern = "#.*['$](.*)'\|t.*#i";
	$pattern = "#({{|{%).*['$](.*)'\|t.*(%}|}})#i";
	$n = 2;
} elseif ($_GET['type'] == 'plugins' || $_GET['type'] == 'includes') {
	$dir = '../../'.$_GET['type'].'/'.$_GET['name'];
	$files = glob($dir.'/'."*.php");
	$pattern = "#(__|_n|_s)\s*\('(.*)'(\)|,)#U";
	$n = 2;
}

$words = array();

foreach($files as $file) {
	$text = file_get_contents($file);
	preg_match_all($pattern, $text, $result, PREG_PATTERN_ORDER);
	array_push_array($words, $result[$n]);
}

$words = array_unique($words);

$po = '
msgid ""
msgstr ""
"Plural-Forms: nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);\n"
"Project-Id-Version: \n"
"POT-Creation-Date: \n"
"PO-Revision-Date: \n"
"Last-Translator: \n"
"Language-Team: \n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
"Language: '.$_GET['lang'].'\n"
"X-Generator: Poedit 1.8\n"
';

if ($_GET['action'] == 'update') {
	$fileLang = $dir.'/locale/'.$_GET['lang'].'/LC_MESSAGES/lang.po';
	$pattern = "#msgid \"(.*)\"(\r\n|\n)msgstr \"(.*)\"#U";
	$text = file_get_contents($fileLang);

	preg_match_all($pattern, $text, $result);

	$words2 = array();

	foreach ($words as $word) {
		$id = array_search($word, $result[1]);
		$words2[$word] = $result[3][$id];
	}
	
	foreach ($words2 as $key => $word) {
		$po .= "msgid \"$key\"\nmsgstr \"$word\"\n\n";
	}

} else {
	foreach ($words as $word) {
		$po .= "msgid \"$word\"\nmsgstr \"\"\n\n";
	}
}

$filename = 'lang.po';
header('HTTP/1.1 200 OK');
header("Pragma: no-cache");
header("Accept-Charset:utf-8"); 
header('Content-Type: application/force-download; charset=utf-8');
header('Content-Description: File Transfer');
header("Content-Disposition: attachment; filename=\"{$filename}\"");
header('Content-Transfer-Encoding: binary');
echo $po;

?>