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
				Tanggal Register : {{ formatDate(request()->get('start_date')) }} - {{ formatDate(request()->get('end_date')) }}
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
				<th>TANGGAL REGISTER</th>
				<th>QTY</th>
			</tr>
		</thead>
		<tbody>
			@php
			$total_berat = 0;
			@endphp

			@forelse($data as $table)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $table->jenis_nama }}</td>
				<td>{{ formatDate($table->register_tanggal) }}</td>
				<td>{{ $table->register_qty }}</td>
			</tr>
			@empty
			@endforelse
			<tr>
				<td>*</td>
				<td colspan="2">Total Register</td>
				<td>{{ $data->sum('register_qty') }}</td>
			</tr>

		</tbody>
	</table>
</div>