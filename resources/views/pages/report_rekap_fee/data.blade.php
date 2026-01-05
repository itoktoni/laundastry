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
				<th style="width: 200px">JENIS LINEN</th>
				@foreach ($tanggal as $tgl)
				<th>{{ $tgl->format('d') }}</th>
				@endforeach
				<th class="text-right">QTY</th>
				<th class="text-right">FEE</th>
				<th class="text-right">JUMLAH</th>
			</tr>
		</thead>
		<tbody>
			@php
			$total = 0;
			@endphp

			@forelse($jenis as $id => $name)
			@php
				$total_jenis = $data->where('jenis_id', $id)->sum('transaksi_bersih');
				$harga = $data->where('jenis_id', $id)->first()->jenis_fee;
				$total_harga = $total_jenis * $harga;
				$total = $total + $total_harga;
			@endphp
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $name }}</td>
				@foreach ($tanggal as $tgl)
				<td class="text-center">
					{{ $data->where('jenis_id', $id)->where('transaksi_report', $tgl->format('Y-m-d'))->sum('transaksi_bersih') }}
				</td>
				@endforeach
				<td class="text-center">{{ $total_jenis }}</td>
				<td class="text-right">{{ $harga }}</td>
				<td class="text-right">{{ $total_harga }}</td>

			</tr>
			@empty
			@endforelse

			<tr>
				<td>*</td>
				<td>Total Semua Linen</td>
				@foreach ($tanggal as $tgl)
				@php
				$jumlah_tgl = $data->where('transaksi_report', $tgl->format('Y-m-d'))->sum('transaksi_bersih');
				@endphp

				<td class="text-center">{{ $jumlah_tgl }}</td>
				@endforeach
				<td class="text-center">{{ $data->sum('transaksi_bersih') }}</td>
				<td class="text-right">TOTAL</td>
				<td class="text-right">{{ $total }}</td>
			</tr>

		</tbody>
	</table>
</div>