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
				@foreach ($tanggal as $tgl)
				<th>{{ $tgl->format('d') }}</th>
				@endforeach
				<th>QTY</th>
			</tr>
		</thead>
		<tbody>
			@php
			$total_berat = 0;
			@endphp

			@forelse($jenis as $id => $name)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $name }}</td>
				@foreach ($tanggal as $tgl)
				<td class="text-center">
					{{ $data->where('jenis_id', $id)->where('transaksi_tanggal', $tgl->format('Y-m-d'))->sum('transaksi_scan') }}
				</td>
				@endforeach
				<td class="text-center">{{ $data->where('jenis_id', $id)->sum('transaksi_scan') }}</td>
			</tr>
			@empty
			@endforelse

			<tr>
				<td>*</td>
				<td>Total Semua Linen</td>
				@foreach ($tanggal as $tgl)
				@php
				$jumlah_tgl = $data->where('transaksi_tanggal', $tgl->format('Y-m-d'))->sum('transaksi_scan');
				@endphp

				<td class="text-center">{{ $jumlah_tgl }}</td>
				@endforeach
				<td class="text-center">{{ $data->sum('transaksi_scan') }}</td>
			</tr>

		</tbody>
	</table>
</div>