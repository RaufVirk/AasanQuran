<?php

	/*

		Single File PHP Gallery TEST FILE 1.5.1
		
		This is a test script for testing if server requirements are met for Single File PHP Gallery to run.
		Place this file in the directory where you want Single File PHP Gallery to be.
		Access the file from a browser. The output should tell if you if Single File PHP Gallery would be able to run.

		Download latest version here:
		http://sye.dk/sfpg/

	*/

	ini_set('error_reporting', E_ALL);
	error_reporting(-1);
	@ob_end_flush();

	if (isset($_GET['x']))
	{
		$x = $_GET['x'];
		$thumb = @imagecreatetruecolor(160,120);
		$image = @imagecreatetruecolor($x,$x);
		if ($image === false)
		{
			exit;
		}
		else
		{
			echo "ok";
			exit;
		}
	}

	if (isset($_GET['s']))
	{
		if ($_GET['s'] === 'sfpg')
		{
			echo "sfpgteststring";
			exit;
		}
		else
		{
			exit;
		}
	}

	function sfpg_file_size($size)
	{
		$sizename = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
		return ($size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2).' '.$sizename[$i] : '0');
	}

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"><html><head>" .
	"<meta http-equiv=\"Content-Type\" content=\"text/html;charset=iso-8859-1\"><title>Single File PHP Gallery TEST FILE</title>" .
	"</head><body>Single File PHP Gallery TEST FILE 1.5.1<br>------------------------------<br>";


	// PHP version
	echo "1: PHP version: " . PHP_VERSION . ". " . (version_compare(PHP_VERSION, "5.0.0") < 0 ? "You need to upgrade PHP to minimum 5.0.0." : "ok.") . "<br>";


	// PHP memory limit
	echo "2: PHP Memory limit: " . ini_get('memory_limit').'<br>';


	// Functions availability
	echo '3: Checking required functions: ';
	$functions=array('array_diff','array_multisort','array_reverse','array_search','array_splice','array_values','base64_decode','base64_encode','closedir','count','create_function','date','date_default_timezone_set','error_reporting','exif_read_data','explode','fclose','file','file_exists','file_get_contents','filemtime','floor','fopen','fwrite','getimagesize','header','htmlentities','imagealphablending','imagecolorallocate','imagecolorat','imagecopyresampled','imagecopyresized','imagecreatefromstring','imagecreatetruecolor','imagedestroy','imagefilledrectangle','imagegif','imagejpeg','imagepng','imagerotate','imagesetpixel','imagesx','in_array','iptcparse','is_array','md5','mkdir','mktime','mt_rand','natcasesort','number_format','opendir','preg_match','readdir','readfile','rmdir','round','rsort','rtrim','scandir','session_start','session_unset','session_write_close','sort','str_replace','strlen','strpos','strrpos','strtolower','strtr','substr','time','trim','unlink');
	$missingFunctions='';
	foreach($functions as $func)
	{
		if (!function_exists($func))
		{
			$missingFunctions .= ' '.$func;
		}
	}
	if ($missingFunctions)
	{
		echo 'Missing Function:'.$missingFunctions.'<br>';
	}
	else
	{
		echo 'All required functions found.<br>';
	}


	// GD info
	echo '4: Checking GD version: ';
	if (function_exists("gd_info"))
	{
		$gdver = gd_info();
		echo "GD version: " . $gdver["GD Version"].'<br>';
	}
	else
	{
		echo "GD library are not installed. You need to add this. See here for information: <a href=\"http://www.php.net/manual/en/book.image.php\">PHP.net - Image Processing and GD</a>";
	}


	// GD image type support
	echo '5: Checking image type support: ';
	if (function_exists("gd_info"))
	{
		$sup='';
		$nonSup='';
		if ($gdver["GIF Read Support"] and $gdver["GIF Create Support"])
		{
			$sup.='GIF ';
		}
		else
		{
			$nonSup.='GIF ';
		}
		if ($gdver["JPG Support"] or $gdver["JPEG Support"])
		{
			$sup.='JPG/JPEG ';
		}
		else
		{
			$nonSup.='JPG/JPEG ';
		}
		if ($gdver["PNG Support"])
		{
			$sup.='PNG ';
		}
		else
		{
			$nonSup.='PNG ';
		}
		if ($sup)
		{
			echo ' Support for: '.$sup;
			if ($nonSup)
			{
				echo '. No support for: '.$nonSup;
			}
		}
		else
		{
			echo 'No support for any required image types (GIF JPG/JPEG PNG)';
		}
	}
	else
	{
		echo "Unable to check image type support due to GD";
	}
	echo '<br>';


	// Memory limit
	echo "6: Testing to find maximum image size handling: ";
	@flush();
	@ob_flush();
	if (function_exists('imagecreatetruecolor'))
	{
		$highPass=0;
		$highFail=0;
		$x=1024;
		While($highFail==0)
		{
			$self_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?x=';
			if (file_get_contents($self_url.$x)==='ok')
			{
				echo "($x&#178;)";
				$highPass=$x;
				$x*=2;
			}
			else
			{
				$highFail=$x;
				echo "(!$x&#178;)";
			}
			@flush();
			@ob_flush();

		}
		$x=$highPass;
		$loop=0;
		echo '-';
		While(($highFail-2 > $highPass) and $loop<15)
		{
			$loop++;
			$x = round(($highFail-$highPass)/2)+$highPass;
			if (file_get_contents($self_url.$x)==='ok')
			{
				$highPass=$x;
				echo "($x&#178;)";
			}
			else
			{
				$highFail=$x;
				echo "(!$x&#178;)";
			}
			@flush();
			@ob_flush();
		}
		echo "<br>6.1: Images need to have less than about ".sfpg_file_size($highPass*$highPass)."Pixels ($highPass x $highPass) for the script to make thumbs.<br>";
		echo "6.2: Rotating images will require twice the memory, so images needing rotation can be maximum ".sfpg_file_size($highPass*$highPass/2)."Pixels .<br>";
	}
	else
	{
		echo "Unable to check maximum image thumb creation due to missing imagecreatetruecolor function.<br>";
	}


	// Check to see that nothing is added to script output
	echo '7: Checking to see if anything is added to script output: ';
	$testStr=file_get_contents('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?s=sfpg');
	if ($testStr === 'sfpgteststring')
	{
		echo 'ok. Nothing is added<br>';
	}
	else
	{
		echo 'FAILED. Something got added. The following should only contain "sfpgteststring", everything besides is added by server, and needs to be turned off:<br>----BEGIN----<pre>'.$testStr.'</pre>----END----<br>';
	}


	// Checking file system permissions
	echo '8: Checking file system permissions.<br>';
	$directory1 = "./sfpg_test_temp_dir/";
	$directory2 = "sfpg_test_temp_dir_2/";
	$filename = "sfpg_test_temp_file.jpg.txt";
	$content = "sfpg file test string";
	
	if (is_dir($directory1))
	{
		echo '<br><br><h2>The test directory "'.$directory1.'" already exists. Delete it and refresh the page.</h2>';
		exit;
	}

	echo '8.1: Creating test directory "'.$directory1.$directory2.'": ';
	if (mkdir($directory1.$directory2, 0777, TRUE) and is_dir($directory1.$directory2))
	{
		echo 'ok.<br>';
	}
	else
	{
		echo 'FAILED.<br>';
	}

	echo '8.2: Creating test file "'.$directory1.$directory2.$filename.'": ';
	if ($handle = fopen($directory1.$directory2.$filename, "w"))
	{
		echo 'ok.<br>';

		echo '8.3: Writing "'.$content.'" to file: ';
		if (fwrite($handle, $content) !== false)
		{
			echo "ok.<br>";
		}
		else
		{
			echo "FAILED.<br>";
		}

		echo '8.4: Closing file: ';
		if (fclose($handle))
		{
			echo "ok.<br>";
		}
		else
		{
			echo "FAILED.<br>";
		}

		echo '8.5: Reading file content: ';
		if (file_get_contents($directory1.$directory2.$filename)===$content)
		{
			echo "ok.<br>";
		}
		else
		{
			echo "FAILED.<br>";
		}

		echo '8.6: Deleting test file: ';
		if ((unlink($directory1.$directory2.$filename) === true) and !file_exists($directory1.$directory2.$filename))
		{
			echo "ok.<br>";
		}
		else
		{
			echo "FAILED.<br>";
		}

		echo '8.7: Deleting test directory "'.$directory1.$directory2.'": ';
		if ((rmdir($directory1.$directory2) === true) and !is_dir($directory1.$directory2))
		{
			echo "ok.<br>";
		}
		else
		{
			echo "FAILED.<br>";
		}

		echo '8.8: Deleting test directory "'.$directory1.'": ';
		if ((rmdir($directory1) === true) and !is_dir($directory1))
		{
			echo "ok.<br>";
		}
		else
		{
			echo "FAILED.<br>";
		}
	}
	else
	{
		echo "FAILED.<br>";
	}

	
	// Checking $_SERVER vars used
	echo '9: Getting SERVER vars: <br>';
	echo '9.1: PHP_SELF: '.$_SERVER['PHP_SELF'].'<br>';
	echo '9.2: SCRIPT_FILENAME: '.$_SERVER['SCRIPT_FILENAME'].'<br>';
	echo '9.3: DOMAIN_NAME (only used if PayPal is enabled): '.$_SERVER['DOMAIN_NAME'].'<br>';
	echo '9.4: REQUEST_URI (only used if Password is set): '.$_SERVER['REQUEST_URI'].'<br>';

	echo "------------------------------<br></body></html>";

?>