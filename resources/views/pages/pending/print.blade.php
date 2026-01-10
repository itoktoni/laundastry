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
        <h1>PEMBAYARAN PENDING</h1>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="invoice-info">
        <h2>Customer: {{ strtoupper($customer->customer_nama ?? '') }}</h2>
        <p>Tanggal: {{ formatDate($model->transaksi_pending_at ?? null) }}</p>
        <p>Code: {{ $model->transaksi_code_pending ?? null }}</p>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="col-no">No.</th>
                    <th class="col-name text-left" style="width:70%;">Nama Jenis Linen</th>
                    <th class="col-qty" style="width:10%;">Pending</th>
                    <th class="col-qty" style="width:10%;">Bayar</th>
                </tr>
            </thead>
            @if(!empty($model))
            <tbody>
                <tr class="item">
                    <td class="col-no">{{ 1 }}</td>
                    <td class="col-name text-left">{{ $jenis->jenis_nama ?? '' }}</td>
                    <td class="col-qty">{{ $model->transaksi_pending ?? '' }}</td>
                    <td class="col-qty">{{ $model->transaksi_bayar ?? '' }}</td>
                </tr>
            </tbody>
            @endif

			<tfoot>
				<tr>
                    <td>*</td>
					<td class="text-left" colspan="2">Sisa Pending</td>
					<td class="col-qty">{{ isset($model->transaksi_pending) ? ( $model->transaksi_pending - $model->transaksi_bayar) : 0 }}</td>
				</tr>
			</tfoot>
        </table>

        <x-footer />

    </div>

</div>
@endsection