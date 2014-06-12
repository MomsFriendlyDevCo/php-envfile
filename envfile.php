<?php
/**
* PHP implementation of the NodeJS envfile system - https://www.npmjs.org/package/envfile
*
* This system works by the dev placing a .env file in the root of the project.
* That file is read for various config information which is imported into scope.
* Its expected that that file is excluded from commits (via .gitignore or whatever) with every dev having their own .env file unique to them.
*
* As an additional sanity measure its recommended that a .env.example file is placed in the same directory containing example settings.
* If present that file will be scanned and the variables imported into .env compared to make sure no settings are missing.
*
* @author Matt Carter <m@ttcarter.com>
* @url https://github.com/MomsFriendlyDevCo/php-envfile
* @package php-envfile
*
*/

/**
* Import settings from an envfile and return them as an assoc array
* @param string $file The file to import from
* @param string $sanityFile The file to use for sanity checks, if any settings are present here but omitted from $file an error will be raised
* @param bool $fatal Fatally exit on errors
* @return array Assoc array of all imported (and optionally sanity checked) settings
*/
function envfile($file = '.env', $sanityFile = '.env.example', $fatal = TRUE) {
	$settings = array();

	if (!file_exists($file)) {
		echo "\n<h1>$file file not found</h1>\n";
		echo "<p>The <code>$file</code> file for this project was not found to import settings.</p>\n";
		if ($sanityFile)
			echo "<p>You can see example settings in the file <code>$sanityFile</code>. You can use the file by copying it into the currently absent <code>$file</code> file</p>\n";
		if ($fatal) {
			echo "<p>Since we cannot continue without this file, we now have to terminate.</p>";
			die();
		}
		return array();
	}

	$fh = fopen($file, 'r');
	while ($line = fgets($fh)) {
		if (preg_match('/^(.*?)(\s*[=:]\s*)(.*)$/', rtrim($line), $matches))
			$settings[$matches[1]] = $matches[3];
	}
	fclose($fh);

	if ($sanityFile && file_exists($sanityFile)) {
		$sanitySettings = envfile($sanityFile, null);

		if ($diff = array_diff_key($sanitySettings, $settings)) {
			echo "\n<h1>.env file error - missing settings</h1>\n";
			echo "<ul>\n";
			foreach ($diff as $key => $where)
				echo "\t<li>{$key} is present in '$sanityFile' but not in '$file'</li>\n";
			echo "</ul>\n";
			if ($fatal)
				die();
		}
	}

	return $settings;
}
