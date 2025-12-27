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
				<th>JENIS LINEN</th>
				<th>TANGGAL PENDING</th>
				<th>PENDING</th>
				<th>BAYAR</th>
				<th>TANGGAL BAYAR</th>
				<th>OUTSTANDING</th>
			</tr>
		</thead>
		<tbody>
			@php
			$total = 0;
			@endphp

			@forelse($data as $table)
			@php
				$sub = ($table->field_pending - $table->transaksi_bayar);
				$total = $total + $sub;
			@endphp
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $table->jenis_nama }}</td>
				<td>{{ formatDate($table->field_report) }}</td>
				<td class="text-center">{{ $table->field_pending }}</td>
				<td class="text-center">{{ $table->transaksi_bayar }}</td>
				<td>{{ formatDate($table->transaksi_pending_at) }}</td>
				<td class="text-center">{{ $sub }}</td>
			</tr>
			@empty
			@endforelse
			<tr>
				<td>*</td>
				<td>Total Summary</td>
				<td>Total Pending</td>
				<td class="text-center">{{ $data->sum('transaksi_pending') }}</td>
				<td class="text-center">{{ $data->sum('transaksi_bayar') }}</td>
				<td>Total Outstanding</td>
				<td class="text-center" colspan="2">{{ $total }}</td>
			</tr>

		</tbody>
	</table>
</div>