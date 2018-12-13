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
		<table style="border-collapse: collapse;" cellpadding="5" width="100%" border="1">
			<tr>
				<td colspan="2" style="font-weight:bold;">Jumlah Transaksi </td>
				<td colspan="3"><?php echo $jml_transaksi; ?></td>
			</tr>
			<tr>
				<td colspan="2" style="font-weight:bold;">Pendapatan </td>
				<td colspan="3">Rp. <?php echo str_replace(",", ".", number_format($pendapatan)); ?></td>
			</tr>
			<tr border="2">
				<th>No.</th>
				<th>Nama Barang</th>
				<th>Harga Jual</th>
				<th>Jml Terjual</th>
				<th>Sub Total</th>
			</tr>
			<tr>
				
			</tr>
			<?php $total=0; if($jml_transaksi==0){ ?>
				<tr>
					<td colspan="5">DATA KOSONG</td>
				</tr>
			<?php }else{ ?>
				<?php $no=0; foreach ($barangTerjual as $barang_terjual) { $total=$total+($barang_terjual->harga_jual_detail*$barang_terjual->qty); ?>
					<tr>
						<td align="center">
							<?php echo ++$no."."; ?>
						</td>
						<td>
							<?php echo $barang_terjual->nama_barang; ?>
						</td>
						<td align="right">
							Rp. <?php echo str_replace(",", ".", number_format($barang_terjual->harga_jual_detail)); ?>		
						</td>
						<td align="center">
							<?php echo $barang_terjual->qty; ?>
						</td>
						<td align="right">
							Rp. <?php echo str_replace(",", ".", number_format($barang_terjual->harga_jual_detail*$barang_terjual->qty)); ?>
						</td>
					</tr>
				<?php } ?>	
			<?php } ?>
			<tr style="font-weight:bold;">
				<td colspan="4" style="text-align: right;">Total Harga : </td>
				<td align="right">Rp. <?php echo str_replace(",", ".", number_format($total)); ?></td>
			</tr>
		</table>
	</div>
</body>
</html>