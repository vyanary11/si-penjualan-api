<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
	<style>@page { size: A4 }</style>
	<style type="text/css">
		body{
			margin: 0;
			padding: 0;
			font: 12pt "Tahoma";
		}
		.container{
			padding: 10px;
			width: 210mm;
			overflow: hidden;
		}
		.head{
			text-align: center;
			margin-bottom: 20px;
		}
		.nama{
			font-weight: bold;
		}
		.foot{
			text-align: center;
		}
	</style>
</head>
<body class="A5">
	<div class="container">
		<div class="head">
			<p>
				<span class="nama">Kantin SD Pelita Hati</span><br>
				<?php 
					if ($lap=="harian") {
			            echo "Laporan Harian</br>";
			        }elseif ($lap=="bulanan") {
			            echo "Laporan Bulanan</br>";
			        }elseif ($lap=="tahunan") {
			            echo "Laporan Tahunan</br>";
			        }
				?>
				Periode <?php echo $periode; ?>
			</p>
		</div>
		
	</div>
</body>
</html>