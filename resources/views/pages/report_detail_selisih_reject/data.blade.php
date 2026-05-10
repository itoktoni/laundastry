<table border="0" class="header">
	<tr>
		<td></td>
		<td colspan="6">
			<h3>
				<b>{{ moduleName() }}</b>
			</h3>
		</td>
		<td rowspan="3">
			<x-logo/>
		</td>
	</tr>
	@if ($customer)
	<tr>
		<td></td>
		<td colspan="10">
			<h3>
				Customer : {{ $customer->customer_nama ?? '' }}
			</h3>
		</td>
	</tr>
	@endif
	<tr>
		<td></td>
		<td colspan="10">
			<h3>
				Tanggal : {{ formatDate(request()->get('start_date')) }} - {{ formatDate(request()->get('end_date')) }}
			</h3>
		</td>
	</tr>
</table>

<div class="table-responsive" id="table_data">
	<table id="export" border="1" style="border-collapse: collapse !important; border-spacing: 0 !important;"
		class="table table-bordered table-striped table-responsive-stack">
		<thead>
			<tr>
				<th width="1">No. </th>
				<th>KODE TRANSAKSI</th>
				<th>JENIS LINEN</th>
				<th>{{ env('LOCATION_LABEL', 'Ruangan') }}</th>
				<th>TANGGAL KOTOR</th>
				<th>OPERATOR</th>
				<th>TGL KOTOR</th>
				<th>KOTOR</th>
				<th>TANGGAL QC</th>
				<th>HASIL QC</th>
				<th>SELISIH SORTIR</th>
				<th>TANGGAL PACKING</th>
				<th>BERSIH</th>
				<th>TANGGAL BERSIH</th>
				<th>PENDING</th>
				<th>BAYAR</th>
				<th>LUNAS</th>
			</tr>
		</thead>
		<tbody>
			@php
			$total_berat = 0;
			@endphp

			@forelse($data as $table)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $table->transaksi_code_scan }}</td>
				<td>{{ $table->jenis_nama }}</td>
				<td>{{ $table->lokasi_nama }}</td>
				<td>{{ formatDate($table->field_tanggal) }}</td>
				<td>{{ $table->name }}</td>
				<td class="text-center">{{ $table->transaksi_created_at }}</td>
				<td class="text-center">{{ $table->field_scan }}</td>
				<td class="text-center">{{ $table->transaksi_qc_at }}</td>
				<td class="text-center">{{ $table->field_qc }}</td>
				<td class="text-center">{{ $table->field_qc - $table->field_scan }}</td>
				<td class="text-center">{{ $table->transaksi_bersih_at }}</td>
				<td class="text-center">{{ $table->transaksi_bersih }}</td>
				<td class="text-center">{{ $table->transaksi_report }}</td>
				<td class="text-center">{{ $table->transaksi_pending }}</td>
				<td class="text-center">{{ $table->transaksi_bayar ?? 0 }}</td>
				<td class="text-center">{{ $table->transaksi_pending - ($table->transaksi_bayar ?? 0) }}</td>
			</tr>
			@empty
			@endforelse

			<tr>
				<td colspan="7" class="text-right"><b>Total</b></td>
				<td class="text-center"><b>{{ $data->sum('field_scan') }}</b></td>
				<td class="text-center"></b></td>
				<td class="text-center"><b>{{ $data->sum('field_qc') }}</b></td>
				<td class="text-center"><b>{{ $data->sum('field_qc') - $data->sum('field_scan') }}</b></td>
				<td class="text-center"></b></td>
				<td class="text-center"><b>{{ $data->sum('transaksi_bersih') }}</b></td>
				<td class="text-center"></b></td>
				<td class="text-center"><b>{{ $data->sum('transaksi_pending') }}</b></td>
				<td class="text-center"><b>{{ $data->sum('transaksi_bayar') }}</b></td>
				<td class="text-center"><b>{{ $data->sum('transaksi_pending') - $data->sum('transaksi_bayar') }}</b></td>
			</tr>

		</tbody>
	</table>
</div>