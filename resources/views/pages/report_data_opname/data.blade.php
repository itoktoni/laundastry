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
				Customer : {{ $model->has_customer->customer_nama ?? '' }}
			</h3>
		</td>
	</tr>
	@endif
	<tr>
		<td></td>
		<td colspan="10">
			<h3>
				Tanggal : {{ formatDate($model->opname_mulai) }} - {{ formatDate($model->opname_selesai) }}
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
				<th>Register</th>
				<th>Opname</th>
				<th>Selisih</th>
			</tr>
		</thead>
		<tbody>
			@php
			$total = 0;
			@endphp

			@forelse($detail as $id => $item)
			@php
			$jenis = $item->first();
			$register = $item->sum('odetail_register');
			$opname = $item->sum('odetail_ketemu');
			$selisih = $register - $opname;
			$total = $total + $selisih;
			@endphp
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $jenis->jenis_nama }}</td>
				<td class="text-center">{{ $register }}</td>
				<td class="text-center">{{ $opname }}</td>
				<td class="text-center">{{ $selisih }}</td>
			</tr>
			@empty
			@endforelse

			<tr>
				<td>*</td>
				<td>Total Perbandingan Linen</td>
				<td class="text-center">{{ $detail->sum('odetail_register') }}</td>
				<td class="text-center">{{ $detail->sum('odetail_ketemu') }}</td>
				<td class="text-center">{{ $total }}</td>
			</tr>

		</tbody>
	</table>
</div>