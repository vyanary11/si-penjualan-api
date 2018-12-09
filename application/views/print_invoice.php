<!DOCTYPE html>
<html>
<head>
	<style type="text/css" media="print">
        @page { 
            width: 58mm;
            margin: 0;
        }
        body{
            margin: 0;
        }
    </style> 
	<style type="text/css">
		body{
			font: 9pt "Tahoma";
		}
		.container{
			padding: 10px;
			max-width: 58mm;
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
				Jl. Blabla</br>
				0811168868
			</p>
		</div>
		<table>
			<tr>
				<td>Tanggal</td>
				<td>:</td>
				<td><?php echo $tgl_transaksi; ?></td>
			</tr>
			<tr>
				<td>No Invoice</td>
				<td>:</td>
				<td>
					<?php 
						if ($jenis_transaksi==0){
							echo "#PL".$kd_transaksi;
						}else{
							echo "#PB".$kd_transaksi;
						} 
					?>	
				</td>
			</tr>
			<tr>
				<td>Kasir</td>
				<td>:</td>
				<td><?php echo $nama_user; ?></td>
			</tr>
			<tr>
				<td>Jml Item</td>
				<td>:</td>
				<td><?php echo $jml_item; ?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td>:</td>
				<td>
					<?php 
						if ($status==0){
							echo "Lunas";
						}else{
							echo "Belum Lunas";
						} 
					?>
				</td>
			</tr>
		</table>
		<table style="border-collapse: collapse;" width="100%">
			<tr>
				<td colspan="2">=================================</td>
			</tr>
			<tr>
				<th style="text-align: left;">Barang</th>
				<th style="text-align: right;">Sub Total</th>
			</tr>
			<tr>
				<td colspan="2">=================================</td>
			</tr>
			<?php foreach ($detailinvoice as $data_detailinvoice) { ?>
				<?php 
					if ($jenis_transaksi==0) {
						$harga=$data_detailinvoice->harga_jual_detail;
					}else{
						$harga=$data_detailinvoice->harga_jual_detail;
					}
				?>
				<tr>
					<td colspan="2">
						<?php echo $data_detailinvoice->nama_barang; ?>
					</td>
				</tr>
				<tr>
					<td>
						<span><?php echo str_replace(",", ".", number_format($data_detailinvoice->qty)); ?></span> x @Rp. <span><?php echo str_replace(",", ".", number_format($harga)); ?></span>
					</td>
					<td align="top" style="text-align: right;">Rp. <?php echo str_replace(",", ".", number_format($data_detailinvoice->qty*$harga)); ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td colspan="2">=================================</td>
			</tr>
			<tr>
				<th style="text-align: right;">Total Harga : </th>
				<th style="text-align: right;">Rp. <?php echo $harga_total; ?></th>
			</tr>
			<tr>
				<td colspan="2">=================================</td>
			</tr>
		</table>
		<div class="foot" align="center">
			<p>
				Terima kasih atas kunjungan anda<br>
				Semoga anda puas dengan layanan kami</br>
			</p>
		</div>
	</div>
</body>
</html>