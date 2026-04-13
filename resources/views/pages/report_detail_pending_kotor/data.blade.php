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
	@if ($model)
	<tr>
		<td></td>
		<td colspan="10">
			<h3>
				Customer : {{ $model->customer_nama ?? '' }}
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
				<th>TGL PENDING</th>
				<th>PENDING</th>
				<th>BAYAR</th>
				<th>SISA</th>
			</tr>
		</thead>
		<tbody>
			@php
				$pending = $data->sum('transaksi_pending');
				$bayar = $data->sum('transaksi_bayar');
				$total = $pending - $bayar;
			@endphp

			@forelse($data as $table)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $table->transaksi_code_scan }}</td>
				<td>{{ $table->jenis_nama }}</td>
				<td>{{ $table->lokasi_nama }}</td>
				<td>{{ formatDate($table->field_report) }}</td>
				<td class="text-center">{{ $table->transaksi_pending }}</td>
				<td class="text-center">{{ $table->transaksi_bayar ?? 0 }}</td>
				<td class="text-center">{{ $table->transaksi_pending - $table->transaksi_bayar }}</td>
			</tr>
			@empty
			@endforelse

			<tr>
				<td>*</td>
				<td colspan="4">Total Summary</td>
				<td class="text-center">{{ $pending }}</td>
				<td class="text-center">{{ $bayar }}</td>
				<td class="text-center">{{ $total }}</td>
			</tr>

		</tbody>
	</table>
</div>