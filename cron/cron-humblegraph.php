<?php

require_once __DIR__ . "/vendor/autoload.php";

// Page to crawl
$page_url = "https://www.humblebundle.com/";

// Connect to mongo and select database
$mongo_connection = new MongoDB\Client("mongodb://localhost:27017");
$mongo_cursorbase   = $mongo_connection->humblegraph;

if ($page_data = file_get_contents($page_url)) {

	// Create regex patterns to get the needed data
	$pattern_name = "/\"machine_name\": \"(.*?)\"/";
	$pattern_title = "/\"human_name\": \"(.*?)\"/";
	$pattern_price = "/\"cleanavg\": \"(.*?)\"/";

	// See if the first pattern matches
	preg_match($pattern_name, $page_data, $matches);

	if (count($matches) > 0) {
		// Only continue if the first match exists
		$name = $matches[1];

		preg_match($pattern_title, $page_data, $matches);
		$title = $matches[1];

		preg_match($pattern_price, $page_data, $matches);
		$price = $matches[1];

		//

		$current_time = time();

		// Select collection for this bundle
		$mongo_collection = $mongo_cursorbase->$name;

		// Add title and timestamp if the collection is new
		$title_document_find = $mongo_collection->findOne(
			array(
				"title" => $title
				)
			);

		if (empty($title_document_find)) {

			$title_document = array(
				"title" => $title,
				"first_date" => $current_time,
				"first_price" => $price,
				);

			$mongo_collection->insertOne($title_document);
		}

		// Add current price and timestamp only if it's a new value
		$options = array(
			"sort" => array("date" => -1),
			"limit" => 1
			);
		$previous_doc = $mongo_collection->findOne(array('price' => array('$exists' => 'true')), $options);

		if ($previous_doc['price'] !== $price) {
			$price_document = array(
				"price" => $price,
				"date" => $current_time
				);

			$mongo_collection->insertOne($price_document);
		}

		// Get all data from current collection
		$mongo_cursor = $mongo_collection->find(array('price' => array('$exists' => 'true')));
		$it = new \IteratorIterator($mongo_cursor);
		$it->rewind(); // Very important

		$total = 0;
		$average_price = 0;

		while ($doc = $it->current()) {

			$date_current_price = $doc['price'];

			if ($doc_next = $it->next()) {
				$date_next_price = $doc_next['price'];
				$date_delta = $doc_next['date'] - $doc['date'];
			} else {
				$date_delta = $current_time - $doc['date'];
			}

			// Calculate real average over time
			$average_price += ((int) $doc['price'] * $date_delta);
			$total += $date_delta;

		}

		$average_price = $average_price / $total;

		// Finish the cron with an update of the title document
		$new_data = array('$set' => array(
			"last_date" => $current_time,
			"average_price" => $average_price,
			"last_price" => $price
			));
		$mongo_collection->updateOne(array("title" => $title), $new_data);

	}

}
