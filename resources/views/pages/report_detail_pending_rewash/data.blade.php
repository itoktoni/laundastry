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
				<th>PIC BAYAR</th>
				<th>SISA</th>
			</tr>
		</thead>
		<tbody>
			@php
				$total = $pending = $bayar = 0;
			@endphp
			@forelse ($data as $jenis_nama => $item)

				@php
					$single = $item->first();
					$pending = $pending + $single->transaksi_pending;
					$bayar = $bayar + $single->transaksi_bayar;
					$sum = $single->transaksi_pending - $single->transaksi_bayar;
					$total = $total + $sum;
				@endphp

				<tr>
					<td>Sum</td>
					<td colspan="2">{{ $jenis_nama }}</td>
					<td class="text-center">{{ $single->transaksi_pending }}</td>
					<td class="text-center">{{ $single->transaksi_bayar }}</td>
					<td colspan="3" class="text-right">{{ $single->transaksi_pending - $single->transaksi_bayar }}</td>
				</tr>

				@forelse($item as $table)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ $table->jenis_nama }}</td>
					<td>{{ formatDate($table->field_report) }}</td>
					<td class="text-center"></td>
					<td class="text-center">{{ $table->pending_qty }}</td>
					<td>{{ formatDate($table->transaksi_pending_at) }}</td>
					<td class="text-center">{{ $table->name }}</td>
					<td></td>
				</tr>
				@empty
				@endforelse

			@empty

			@endforelse

			<tr>
				<td>*</td>
				<td>Total Summary</td>
				<td>Total Pending</td>
				<td class="text-center">{{ $pending }}</td>
				<td class="text-center">{{ $bayar }}</td>
				<td>Total Outstanding</td>
				<td class="text-right" colspan="2">{{ $total ?? '0' }}</td>
			</tr>

		</tbody>
	</table>
</div>