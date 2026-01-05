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
				<th rowspan="2" width="1">No. </th>
				<th rowspan="2">JENIS LINEN</th>
				@foreach ($tanggal as $tgl)
				<th class="text-center" colspan="2">{{ $tgl->format('d') }}</th>
				@endforeach
				<th rowspan="2">OUTSTANDING</th>
			</tr>
			<tr>
				@foreach ($tanggal as $tgl)
				<th>Pending</th>
				<th>Bayar</th>
				@endforeach
			</tr>
		</thead>
		<tbody>
			@php
			$total = 0;
			@endphp

			@forelse($jenis as $id => $name)

			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $name }}</td>
				@foreach ($tanggal as $tgl)

				@php
				$pending = $data->where('jenis_id', $id)->where('transaksi_report', $tgl->format('Y-m-d'))->sum('transaksi_pending');
				$bayar = $data->where('jenis_id', $id)->where('transaksi_report', $tgl->format('Y-m-d'))->sum('transaksi_bayar');
				$sub = $pending - $bayar;
				$total = $total + $sub;
				@endphp

				<td class="text-center">
					{{ $pending }}
				</td>
				<td class="text-center">
					{{ $bayar }}
				</td>
				@endforeach
				<td class="text-center">{{ $sub }}</td>
			</tr>
			@empty
			@endforelse

			<tr>
				<td>*</td>
				<td colspan="{{ 1 + (count($tanggal) * 2) }}">Total Outstanding</td>
				<td class="text-center">{{ $total }}</td>
			</tr>

		</tbody>
	</table>
</div>