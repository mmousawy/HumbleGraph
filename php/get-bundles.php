<?php
// Connect to mongo and select database
if (class_exists('MongoClient') && $mongo_connection = new MongoClient()) {
	$mongo_database   = $mongo_connection->humblegraph;

	// Get all data from current collection
	$mongo_data = $mongo_database->listCollections();

	$output_data = [];

	foreach ( $mongo_data as $index => $collection ) {
		$row = $collection->findOne(
			array(
				"title" => array('$exists' => "true")
				)
			);

		if (!empty($row["title"])) {
			$output_row = [];

			$output_row['title'] = $row["title"];
			$output_row['name'] = $collection->getName();
			$output_row['average_price'] = $row["average_price"];
			$output_row['first_price'] = $row["first_price"];
			$output_row['last_price'] = $row["last_price"];
			$output_row['first_date'] = $row["first_date"];
			$output_row['last_date'] = $row["last_date"];

			$output_data[] = $output_row;
		}
	}

	echo json_encode($output_data);
} else {
	echo "Cannot connect to Mongo";
}
