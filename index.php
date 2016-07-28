<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>HumbleGraph</title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
	<link href='//fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="assets/css/main.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.6/Chart.bundle.min.js"></script>
	<script src="assets/js/jquery.tablesorter.min.js"></script>
	<header class="header--page">
		<div class="header__content">
			<h2 class="title--logo"><a href="/humblegraph/"><i class="fa fa-line-chart" aria-hidden="true"></i> HumbleGraph</a></h2></div>
	</header>
	<main>
		<div class="main__content">
			<article>
				<header class="header--main">
					<h1 class="title--page"></h1>
				</header>
				<canvas class="canvas--graph"></canvas>
				<div class="box-bundles">
					<table id="sorter" class="table-bundles">
						<thead>
							<tr>
								<th>Bundle name</th>
								<th>Avg. price</th>
								<th>First price</th>
								<th>Last price</th>
								<th>First seen</th>
								<th>Last seen</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</article>
		</div>
	</main>
	<footer>
		<div class="footer__content">
			<p class="footer-text--copyright">Made with <i class="fa fa-heart" aria-hidden="true"></i> by Murtada al Mousawy</p>
			<p class="footer-text--social"><a href="https://github.com/doubtingreality" target="_blank"><i class="fa fa-github-alt" aria-hidden="true"></i></a><a href="https://twitter.com/mmousawy" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a><a href="//murtada.nl/mail/"><i class="fa fa-at" aria-hidden="true"></i></a></p>
			<p class="footer-text--disclaimer">HumbleGraph is not affiliated or supported by Humble Bundle. All Humble Bundle referenced material used on these pages are publicly available and used for non-profit and educational purposes.</p>
		</div>
	</footer>
	<script src="assets/js/main.js"></script>
</body>
</html>