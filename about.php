<?php
include 'baseURL.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Greenwater Village | About Us</title>
	<link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
	<?php include 'partials/header.php'; ?>

	<main class="about-main main-content">
		<section class="about-header">
			<h1 class="about-title">Greenwater Village</h1>
			<hr class="icon-hr"/>

		</section>
				<section class="about-section mission-vision">
			<h2>Our Mission</h2>
			<p class="mission">
				"To serve and empower our community, protect our environment, and promote the well-being of Greenwater Village residents through quality services, sustainable practices, and community engagement."
			</p>

			<h2>Our Vision</h2>
			<p class="vision">
				"By 2035, Greenwater Village Barangay aims to become a thriving, faith-driven community that fosters resilience, sustainability, and equal opportunities for all,
				while preserving our cultural heritage and promoting environmental stewardship, with globally competitive and strategic leadership."
			</p>
		</section>

		<section class="about-section">
			<h2>Legal Basis of Barangay Existence</h2>
			<p>
				The Barangay came into existence on August 6, 1971, during the term of Mayor Luis Lardizabal. Through the guidance and assistance of the Association of Barrio Council, this was then headed by Mr. Federico Librado.
				Mr. Lawrence Baon was appointed as Barrio Captain to initially administer the affairs of the newly created Barrio pending the election of a regular Barrio Captain and its Barangay Council.
				The first election in the Barrio was held on January 9, 1972. The term Barrio was later changed by virtue of a law to Barangay which is still used today.
			</p>
		</section>

		<hr class="section-divider" />

		<section class="about-section">
			<h2>History of Greenwater</h2>
			<p>
				The neighborhood association thought GREENWATER VILLAGE as its official name of the new born barrio because of the presence of spring water that seems to appear green in color because of the moss abounding it.
				Another reason is that the terms green and water go together: green plants help produce water while water makes plants look fresh and green.
			</p>
		</section>

		<hr class="section-divider" />


	</main>

	<?php include 'partials/footer.php'; ?>
	<style>
		.about-main {
			padding: 2rem 1rem;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background-color: #f9f9f9;
			color: #1e2b2d;
		}

		.about-header {
			text-align: center;
			margin-bottom: 1.5rem;
		}

		.about-title {
			font-size: 2.2rem;
			font-weight: bold;
			color: #004225;
			margin-bottom: 0.5rem;
			text-transform: uppercase;
			letter-spacing: 1px;
		}
		.icon-hr {
			border: none;
			height: 60px;
			background-image: url('assets/img/pine-tree.png');
			background-repeat: repeat-x;
			background-size: 60px auto;
			background-position: center;
			margin: 1rem auto 2rem auto;
			width: 200px;
		}

		.about-section {
			margin-bottom: 2.5rem;
			max-width: 1000px;
			margin-left: auto;
			margin-right: auto;
			padding: 0 1rem;
		}

		.about-section h2 {
			font-size: 1.5rem;
			color: #036635;
			margin-bottom: 0.75rem;
			border-left: 6px solid #004225;
			padding-left: 10px;
		}

		.about-section p {
			line-height: 1.7;
			font-size: 1rem;
			text-align: justify;
		}

		.section-divider {
			border: 0;
			height: 2px;
			background-color: #c3c3c3;
			width: 80%;
			margin: 2rem auto;
		}

		.mission-vision .mission,
		.mission-vision .vision {
			font-style: italic;
			background: #e7f5e8;
			padding: 1rem;
			border-left: 5px solid #00723c;
			margin-top: 1rem;
			margin-bottom: 2rem;
			border-radius: 4px;
			font-size: 1.05rem;
		}
	</style>
	<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .main-content {
        flex: 1;
        display: column;
        justify-content: center;
        align-items: center;
    }
</style>
</body>
</html>
