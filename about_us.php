<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GreenWater Village</title>
	<style>
		.header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 20px 50px;

		}

		.logo-section {
			display: flex;
			align-items: center;
		}

		.text {
			margin-left: 20px;
			font-size: 1.5em;
			font-weight: bold;
		}

		nav {
			display: flex;
			gap: 30px;
		}

		nav a {
			text-decoration: none;
			color: black;
			font-weight: 500;
		}
		nav button {
			border-radius: 50px;
			width: 75px;
			length: 75px;
			background-color: green;
			color: white;
			border: solid;
		}
		.top-left {
			position: absolute;
			top: 8px;
			left: 16px;
		}
		.photo img{
			width: 100%;
			height: 500px;
		}

	</style>
</head>
<body>
	<div class="header">
		<div class="logo-section">
			<img src="logo.png" alt="logo" width="100" height="100">
			<div class="text">GreenWater Village</div>
		</div>
		<nav>
			<a href="#">Home</a>
			<a href="#">About Us</a>
			<a href="#">Events</a>
			<a href="#">Demographics</a>
			<button type="login">Log in</button>
		</nav>
	</div>
	<div class="photo">
			<img src="greenwater.jpg" alt="photo">
	</div>
</body>
</html>
