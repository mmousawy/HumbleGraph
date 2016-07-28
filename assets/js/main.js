var myLineChart;

$(document).ready(function() {
	var url = document.location.href.split("humblegraph/");
	var bundle_name = false;

	if (url[1]) {
		bundle_name = url[1];
	}

	getBundles(bundle_name);

	$("table.table-bundles").on("click", "a.link-bundle", function(event) {
		event.preventDefault();
		openBundle($(this));
		return false;
	});

	window.onpopstate = function(e) {
		console.log(e);
		if (e.state) {
			console.log(e.state);
			document.getElementById("content").innerHTML = e.state.html;
			document.title = e.state.pageTitle;
		}
	};
});

function addRow(table, data) {
	var row = table.insertRow(-1);

	for (var index in data) {
		var cell = row.insertCell(index);
		cell.innerHTML = data[index];
	}
}

function getData(bundle_name) {
	var request = new XMLHttpRequest();
	request.open('GET', 'assets/php/get-data.php?bundle_name='+bundle_name, true);

	request.onload = function() {
		if (request.status >= 200 && request.status < 400) {
			if (IsJsonString(request.responseText)) {
				var json_response = JSON.parse(request.responseText);

				var labels = [];
				var data_points = [];

				for (var index in json_response.data) {
					var data_point = json_response.data[index];
					//var clean_date = convertTimestamp();
					labels.push(data_point.date*1000);
					data_points.push(data_point.price);
				}

				var data = {
					labels: labels,
					datasets: [
					{
						label: json_response.title,
						fill: false,
						lineTension: 0.1,
						backgroundColor: "rgba(75,192,192,0.4)",
						borderColor: "rgba(75,192,192,1)",
						borderCapStyle: 'butt',
						borderDash: [],
						borderDashOffset: 0.0,
						borderJoinStyle: 'miter',
						pointBorderColor: "rgba(75,192,192,1)",
						pointBackgroundColor: "#fff",
						pointBorderWidth: 1,
						pointHoverRadius: 5,
						pointHoverBackgroundColor: "rgba(75,192,192,1)",
						pointHoverBorderColor: "rgba(0,0,0,0.5)",
						pointHoverBorderWidth: 2,
						pointRadius: 0,
						pointHitRadius: 8,
						data: data_points,
					}
					]
				};

				if (myLineChart != null) {
					myLineChart.destroy();
				}

				myLineChart = new Chart(document.getElementsByClassName('canvas--graph')[0].getContext('2d'), {
					type: 'line',
					data: data,
					options: {
						scales: {
							xAxes: [{
								type: 'time'
							}]
						},
						legend: {
							display: false
						},
						tooltips: {
							callbacks: {
								title: function(tooltipItem, data) {
									return convertTimestamp(data.labels[tooltipItem[0].index]/1000, 0);
								},
								label: function(tooltipItem, data) {
									var allData = data.datasets[tooltipItem.datasetIndex].data;
									var tooltipLabel = data.labels[tooltipItem.index];
									var tooltipData = allData[tooltipItem.index];
									var total = 0;
									for (var i in allData) {
										total += allData[i];
									}
									return convertTimestamp((tooltipLabel/1000), 2) + ' - $' + tooltipData;
								}
							}
						}
					}
				});

				document.querySelectorAll("h1.title--page")[0].innerHTML = json_response.title;

				var table = document.querySelectorAll(".table-bundles tbody")[0];
			} else {
				console.error("Reponse not valid JSON");
				console.log(request.responseText);
			}

		} else {
			alert("Could not load data: Error status "+request.status);
		}
	};

	request.onerror = function() {
	};

	request.send();
}

function getBundles(bundle_name) {
	var request = new XMLHttpRequest();
	request.open('GET', 'assets/php/get-bundles.php', true);

	request.onload = function() {
		if (request.status >= 200 && request.status < 400) {
			if (IsJsonString(request.responseText)) {
				var json_response = JSON.parse(request.responseText);

				var table_bundles = document.querySelectorAll(".table-bundles tbody")[0];

				for (var index in json_response) {
					var data_row = json_response[index];

					var title = "<a href='"+data_row.name+"' class='link-bundle'>"+data_row.title+"</a>";

					addRow(table_bundles, [title, data_row.average_price.toFixed(2), data_row.first_price, data_row.last_price, convertTimestamp(data_row.first_date, 1), convertTimestamp(data_row.last_date, 1)]);
				}

				$(".table-bundles").tablesorter({sortList: [[5,0], [5,0]]});

				if (bundle_name) {
					var targeted_bundle_link = $("table.table-bundles a.link-bundle[href='"+bundle_name+"']");

					if (targeted_bundle_link.length > 0) {
						$("table.table-bundles tr a.link-bundle[href='"+bundle_name+"']").click();
						return;
					}
				}

				$("table.table-bundles tr:first-child a.link-bundle").click();
			} else {
				console.error("Response not valid JSON");
				console.log(request.responseText);
			}

		} else {
			alert("error");
		}
	};

	request.onerror = function() {
	};

	request.send();
}

function openBundle($bundle_link) {
	var bundle_href = $bundle_link.attr("href");
	var bundle_title = $bundle_link[0].textContent;

	getData(bundle_href);

	$("table.table-bundles tr.selected").removeClass("selected");

	$bundle_link.parent().parent().addClass("selected");

	window.history.pushState({"html":{},"pageTitle":bundle_title},"", bundle_href);
}

function convertTimestamp(UNIX_timestamp, style) {
	var a = new Date(UNIX_timestamp * 1000);
	var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	var year = a.getFullYear().toString().substr(2,2);
	var year_full = a.getFullYear();
	var month = months[a.getMonth()];
	//var month = ('0' + (a.getMonth()+1)).slice(-2);
	var date = a.getDate();
	var hour = ('0' + a.getHours()).slice(-2);
	var min = ('0' + a.getMinutes()).slice(-2);
	var sec = ('0' + a.getSeconds()).slice(-2);

	switch (style) {
		case 0:
		return date + ' ' + month +' '+ year_full;
		break;
		case 1:
		return date + ' ' + month + ' \'' + year;
		break;
		case 2:
		return hour +":"+ min;
		break;
	}
	return 'no style';
}

function IsJsonString(str) {
	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}
	return true;
}