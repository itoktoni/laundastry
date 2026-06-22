@extends('layouts.print')

@section('header')

<x-action_print/>

@endsection

@section('content')

<div class="invoice">

    <!-- HEADER -->
    <div class="invoice-header">
        @if ($customer)
        <x-header :customer="$customer"/>
		@endif
        <h1 style="background-color: {{ empty(request('tanggal')) && !empty($model->transaksi_report) ? 'red' : '' }};"> DELIVERY BERSIH</h1>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="invoice-info">
        <h2>Customer: {{ strtoupper($customer->customer_nama ?? '') }}</h2>
        <h3>{{ env('LOCATION_LABEL', 'Ruangan') }}: {{ strtoupper($lokasi->lokasi_nama ?? '') }}</h3>
        <p>Tanggal: {{ formatDate($model->transaksi_report) }}</p>
        <p>Code: {{ $model->transaksi_code_bersih ?? null }}</p>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="col-no">No.</th>
                    <th class="col-name text-left" style="width:70%;">Nama Jenis Linen</th>
                    <th class="col-qty" style="width:15%;">Qty</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $table)
					<tr class="item {{ $loop->last ? 'last' : '' }}">
						<td class="col-no">{{ $loop->iteration }}</td>
						<td class="col-name text-left">{{ $table->jenis_nama }}</td>
						<td class="col-qty">{{ $table->transaksi_bersih }}</td>
					</tr>
				@empty
					<tr class="item last">
						<td colspan="3">No data available</td>
					</tr>
				@endforelse
            </tbody>
			<tfoot>
				<tr>
					<td colspan="2" style="text-align: right">Total</td>
					<td class="col-qty">{{ $data->sum('transaksi_bersih') }}</td>
				</tr>
			</tfoot>
        </table>

        <x-footer />

    </div>

</div>

@endsection