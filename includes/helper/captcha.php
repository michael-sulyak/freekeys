<?php
/*
Name: Captcha
Version: 0.01
Author: expert_m
*/

function captcha($id, $cod) {
	if ($id && $cod) {
		if (strnatcasecmp($_SESSION['captcha_'.$id], $cod) == 0) {
			return true;
		} else {
			return false;
		}
	} elseif ($id) {
		return $_SESSION['captcha_'.$id];
	}

	return false;
}

?>