@extends('layouts.print')

@section('header')

<x-action_print/>

@endsection

@section('content')

<div class="invoice">

    <!-- HEADER -->
    <div class="invoice-header">
        @if ($customer)
        <h1>
            <img src="{{ imageUrl($customer->customer_logo, 'customer') }}" alt="">
        </h1>
		@endif
        <h1> DETAIL TRANSAKSI {{ $type }}</h1>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="invoice-info">
        <h2>Customer: {{ strtoupper($customer->field_name ?? '') }}</h2>
        <p>Tanggal: {{ formatDate($model->field_tanggal) }}</p>
        <p>Code: {{ $model->field_code ?? null }}</p>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="col-no">No.</th>
                    <th class="col-name text-left" style="width:70%;">Nama Jenis Linen</th>
                    <th class="col-qty" style="width:10%;">Qty</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $table)
					<tr class="item {{ $loop->last ? 'last' : '' }}">
						<td class="col-no">{{ $loop->iteration }}</td>
						<td class="col-name text-left">{{ $table->jenis_nama }}</td>
						<td class="col-qty">{{ $table->transaksi_scan }}</td>
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
					<td class="col-qty">{{ $data->sum('transaksi_scan') }}</td>
				</tr>
			</tfoot>
        </table>

        <table class="footer">
            <p>
                {{ $customer->customer_alamat ?? '' }}, {{ date('d F Y') }}
            </p>
            <br>
            <p>
                {{ auth()->user()->name ?? '' }}
            </p>
            <br>
            <span>.</span>
        </table>

    </div>

</div>

@endsection