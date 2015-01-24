<?php


function fetchFromRSI($page) {
	$memUrl = 'https://robertsspaceindustries.com/api/orgs/getOrgMembers';
	$data = array('symbol' => 'ODDYSEE', 'pagesize' => '255', 'page' => $page );

	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data),
		),
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($memUrl, false, $context);

	$var = json_decode($result, true);
	$str = $var["data"]["html"];

	echo "succ " . $var["success"];
	if (!strlen($str) || $var["success"] != "1" ) {
		return false;
	}

	$DOM = new DOMDocument;
	$DOM->loadHTML($str);

	$items = $DOM->getElementsByTagName('a');
	for ($i = 0; $i < $items->length; $i++) {
		$href = $items->item($i)->getAttribute('href');
		$temp = split('/', $href);

		if (sizeof($temp) == 3) {
			$handle = $temp[2];
			$children = $items->item($i)->childNodes;

			for ($j = 0; $j < $children->length; $j++) {
				$child = $children->item($j);
				$cnodes = $child->childNodes;
				if ($cnodes) {
					if ($cnodes->length == 10) {
						$img = $cnodes->item(1)->getAttribute('src') . "\n";
					} else if ($cnodes->length == 5) {
						$role = $cnodes->item(1)->childNodes->item(1)->nodeValue;
						$roles = array();
						if ($cnodes->item(1)->childNodes->length >= 4 && $cnodes->item(1)->childNodes->item(3)->childNodes->length > 0 ) {
							$roleitems = $cnodes->item(1)->childNodes->item(3)->getElementsByTagName('li');
							if ($roleitems) {
								for ($k = 0; $k < $roleitems->length; $k++) {
									array_push($roles, $roleitems->item($k)->nodeValue);
								}
							}
						}

						if ($cnodes->item(3)->childNodes->length >= 2) {
							$name = $cnodes->item(3)->childNodes->item(1)->childNodes->item(1)->nodeValue;
							$rank = $cnodes->item(3)->childNodes->item(5)->nodeValue;
						}
					}
				}
			}
			$userarr = array( "handle" => $handle, "name" => $name, "img" => $img, "role" => $role, "roles" => $roles, "rank" => $rank );
			insertOrUpdate($userarr);
		} else {
			// ignore reducted user
//             echo "REDUCTED" . "\n";
		}
	}
	return true;
}

function insertOrUpdate($user) {
//     echo "user " . $user["name"] . "\n";
}


?>
