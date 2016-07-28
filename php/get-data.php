<?php

if (!empty($_GET['bundle_name']) && $name = trim($_GET['bundle_name'])) {
	// Connect to mongo and select database
	$mongo_connection = new MongoClient();
	$mongo_database   = $mongo_connection->humblegraph;

	// Select collection for this bundle
	$mongo_collection = $mongo_database->$name;

	// Get all data from current collection
	$mongo_data = $mongo_collection->find()->sort( array( 'date' => 1 ) );

	$output_data = [];
	$output_data['data'] = [];

	foreach ( $mongo_data as $index => $row ) {
		if (!empty($row["title"])) {
			$output_data['title'] = $row["title"];
			$output_data['name'] = $name;
			$output_data['average_price'] = $row["average_price"];
			$output_data['first_price'] = $row["first_price"];
			$output_data['last_price'] = $row["last_price"];
			$output_data['first_date'] = $row["first_date"];
			$output_data['last_date'] = $row["last_date"];
		} else {
			$output_data['data'][] = $row;
		}
	}

	echo json_encode($output_data);
}
