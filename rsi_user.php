<?php
echo "fetch\n";

$url = 'https://robertsspaceindustries.com/api/orgs/getOrgMembers';
$data = array('symbol' => 'ODDYSEE', 'pagesize' => '255', 'page' => '2' );

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);


echo "----- done\n";
#var_dump($result);

$var = json_decode($result, true);
echo "result2:\n";
$str = $var["data"]["html"];
var_dump($str);

// $str = '<h1>T1</h1>Lorem ipsum.<h1>T2</h1>The quick red fox...<h1>T3</h1>... jumps over the lazy brown FROG';
$DOM = new DOMDocument;
$DOM->loadHTML($str);

$items = $DOM->getElementsByTagName('a');
for ($i = 0; $i < $items->length; $i++) {
	$href = $items->item($i)->getAttribute('href');
    $temp = split('/', $href);

	if (sizeof($temp) == 3) {
//         echo "handle " . $temp[2] . "\n";
		$handle = $temp[2];

		$children = $items->item($i)->childNodes;
//         echo "---" . $handle . "\n";

		for ($j = 0; $j < $children->length; $j++) {
			$child = $children->item($j);
			$cnodes = $child->childNodes;
			if ($cnodes) {
//                 echo ">>>> " . $child->nodeName . " | " . $child->childNodes->length . "\n";
				
				if ($cnodes->length == 10) {
					$img = $cnodes->item(1)->getAttribute('src') . "\n";
//                     echo "   img >>>> " . $cnodes->item(1)->getAttribute('src') . "\n";
				} else if ($cnodes->length == 5) {
//                     echo "   role >>>> " . $cnodes->item(1)->getAttribute('class') . "\n";
					$role = $cnodes->item(1)->childNodes->item(1)->nodeValue;
					$roles = array();
//                     echo "   role >>>> " . $cnodes->item(1)->childNodes->item(1)->nodeValue . " | " . $cnodes->item(1)->childNodes->length ."\n";
					if ($cnodes->item(1)->childNodes->length >= 4 && $cnodes->item(1)->childNodes->item(3)->childNodes->length > 0 ) {
						$roleitems = $cnodes->item(1)->childNodes->item(3)->getElementsByTagName('li');
//                         echo "   role >>>> " . $roleitems->length . "\n";
						if ($roleitems) {
							for ($k = 0; $k < $roleitems->length; $k++) {
//                                 echo "             >>>> " . $roleitems->item($k)->nodeValue . "\n";
								array_push($roles, $roleitems->item($k)->nodeValue);
							}
						}
					}

//                     echo "   name >>>> " . $cnodes->item(3)->childNodes->length ."\n";
					if ($cnodes->item(3)->childNodes->length >= 2) {
//                         echo "   name >>>> " . $cnodes->item(3)->childNodes->item(1)->childNodes->item(1)->nodeValue . "\n";
//                         echo "   rank >>>> " . $cnodes->item(3)->childNodes->item(5)->nodeValue . "\n";
						$name = $cnodes->item(3)->childNodes->item(1)->childNodes->item(1)->nodeValue;
						$rank = $cnodes->item(3)->childNodes->item(5)->nodeValue;
					}
				}

//                 for ($k = 0; $k < $cnodes->length; $k++) {
//                     $c = $cnodes->item($k);
//                     echo "   >>>> " . $c->nodeName . "\n";
//                 }

			}
		}
//         echo "---" . $handle . " | " . $name .  "\n";
		$userarr = array( "handle" => $handle, "name" => $name, "img" => $img, "role" => $role, "roles" => $roles, "rank" => $rank );
		print_r($userarr);

//         echo "------- " . print_r($children->item(0)) . "\n"; // ->firstChild->getAttribute('src');

//         if ($children->length > 0)
//         {
//             $child = $children->item(0);
//             echo ">>>> " . $child->nodeType . "\n";
//         }


		/*
		$it = $items->item($i)->getElementsByTagName('img');
		if ($it->length == 1) {
			$img = $it->item(0)->getAttribute('src');
		}
		 */
//         echo "handle " . $temp[2] . " | " . $img . "\n";

	} else {
		echo "REDUCTED" . "\n";
	}
}

// function getTag($tagname,$functions) {
// };
// $items = $DOM->getElementsByTagName('a');

?>

