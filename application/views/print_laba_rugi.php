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
				Laporan Laba Rugi</br>
				Periode <?php echo $periode; ?>
			</p>
		</div>
		<table style="border-collapse: collapse;" cellpadding="5" width="100%" border="1">
			<tr>
				<td width="33%" style="font-weight:bold;"><u>Pendapatan</u></td>
				<td width="33%"></td>
				<td width="33%"></td>
			</tr>
			<tr>
				<td>Penjualan</td>
				<td align="right">
					
				</td>
				<td align="right">
					<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rp. <?php echo str_replace(",", '.', number_format($income)); ?></u></br>
				</td>
			</tr>
			<tr>
				<td style="font-weight:bold;">Laba Kotor</td>
				<td align="right">
					
				</td>
				<td align="right" style="font-weight:bold;">
					Rp. <?php echo str_replace(",", '.', number_format($income)); ?>
				</td>
			</tr>
			<tr>
				<td style="font-weight:bold;"><u>Biaya Pengeluaran</u></td>
				<td></td>
				<td></td>
			</tr>
			<?php foreach ($data_biaya as $biaya): ?>
			<tr>
				<td><?php echo $biaya->nama_biaya; ?></td>
				<td align="right">
					Rp. <?php echo str_replace(",", '.', number_format($biaya->jumlah_biaya)); ?>
				</td>
				<td align="right">
					
				</td>
			</tr>	
			<?php endforeach ?>
			<tr>
				<td>Biaya Pengeluaran Lainya</td>
				<td align="right">
					<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rp. <?php echo $expense; ?></u>
				</td>
				<td align="right">
					
				</td>
			</tr>	
			<tr>
				<td style="font-weight:bold;">Total :</td>
				<td align="right">
				</td>
				<td align="right" style="font-weight:bold;">
					<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rp. <?php echo str_replace(",", '.', number_format($totalbiaya)); ?></u>
				</td>
			</tr>
			<tr>
				<td style="font-weight:bold;">Laba Bersih</td>
				<td align="right">
				</td>
				<td align="right" style="font-weight:bold;">
					Rp. <?php echo str_replace(",", '.', number_format($net_income)); ?>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>