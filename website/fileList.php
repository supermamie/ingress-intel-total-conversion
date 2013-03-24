<?php
	$path = "release";
	
	if ( $_REQUEST['build'] == 'dev' )
		$path = "dev";
	else if(trim($_REQUEST['build'])!='' && strpos(trim($_REQUEST['build']), '.')===false)
		$path = '../build/'.trim($_REQUEST['build']);
	
	function loadUserScriptHeader($path)
	{
		unset($result);
	
		$f = fopen ( $path, "rt" );
		while ( ( $line = fgets ( $f ) ) !== FALSE )
		{
			if ( preg_match ( '#//[ \\t]*==/UserScript==#', $line ) )
				break;
	
			$matches = Array();
			if ( preg_match ( '#^//[ \\t]*@([a-zA-Z0-9]+)[ \\t]+(.*)$#', $line, $matches ) )
			{
				$name = $matches[1];
				$value = $matches[2];
	
				if ( ! array_key_exists ( $name, $result ) )
				{
					$result->$name = $value;
				}
			}
		}
	
		fclose ( $f );
	
		return $result;
	}
	
	$iitc_details = loadUserScriptHeader ( $path . '/total-conversion-build.user.js' );
	
	$plugins = array();
	
	foreach ( glob ( $path . '/plugins/*.user.js' ) as $path )
	{
		$details = loadUserScriptHeader ( $path );
		$plugins[] = $details;
	}
	
	unset($result);
	$result->main = $iitc_details;
	$result->plugins = $plugins;
	
	echo json_encode($result);
?>
