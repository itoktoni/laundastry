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
        <h1>PEMBAYARAN PENDING</h1>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="invoice-info">
        <h2>Customer: {{ strtoupper($customer->customer_nama ?? '') }}</h2>
        <p>Tanggal: {{ formatDate($model->transaksi_pending_at) }}</p>
        <p>Code: {{ $model->transaksi_code_pending ?? null }}</p>
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
                <tr class="item">
                    <td class="col-no">{{ 1 }}</td>
                    <td class="col-name text-left">{{ $jenis->jenis_nama }}</td>
                    <td class="col-qty">{{ $model->transaksi_bayar }}</td>
                </tr>
            </tbody>
			<tfoot>
				<tr>
					<td colspan="2" style="text-align: right">Total</td>
					<td class="col-qty">{{ $model->transaksi_bayar }}</td>
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