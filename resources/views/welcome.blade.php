<html>
	<head>
		<title>Circular - Magazine circulation manager</title>
		
		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: 'gray';
				display: table;
				font-weight: 100;
				font-family: 'Lato';
                background-color: #c2e0ff;
			}

			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
			}

			.title {
				font-size: 96px;
				margin-bottom: 40px;
			}

			.quote {
				font-size: 32px;
			}

            .home {
                font-size: 18px;
                text-decoration: none;
                color: 'blue';
            }

            .home:visited {
                color: 'blue';
            }
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
                <div><img src={{ asset('img/sircular-logo.png') }}></div>
				<div class="quote">Sistem informasi sirkulasi</div>
                <div class="row">
                    <a class='home' href='home'>home</a>
                </div>
			</div>
		</div>
	</body>
</html>
