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
				<th style="width:200px">JENIS LINEN</th>
				<th style="width: 50px">TYPE</th>
				@foreach ($tanggal as $tgl)
				<th>{{ $tgl->format('d') }}</th>
				@endforeach
				<th class="text-right">QTY</th>
				<th class="text-right">HARGA</th>
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
				$single = $data->where('jenis_id', $id)->first();
				$harga = $single->jenis_harga;
				$type = $single->jenis_type;
				$total_harga = $total_jenis * $harga;
				$total = $total + $total_harga;
			@endphp
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $name }}</td>
				<td>{{ $type }}</td>
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
				<td colspan="2">Total Semua Linen</td>
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