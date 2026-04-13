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
        <h1>DETAIL PENDING</h1>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="invoice-info">
        <h2>Customer: {{ strtoupper($customer->customer_nama ?? '') }}</h2>
        <h3>{{ env('LOCATION_LABEL', 'Ruangan') }}: {{ strtoupper($lokasi->lokasi_nama ?? '') }}</h3>
        <p>Tanggal: {{ formatDate($pending->pending_created_at ?? null) }}</p>
        <p>Code: {{ $pending->pending_code ?? null }}</p>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="col-no">No.</th>
                    <th class="col-name text-left" style="width:70%;">Nama Jenis Linen</th>
                    <th class="col-qty" style="width:10%;">Pending</th>
                </tr>
            </thead>
            @if(!empty($model))
            <tbody>
                <tr class="item">
                    <td class="col-no">{{ 1 }}</td>
                    <td class="col-name text-left">{{ $jenis->jenis_nama ?? '' }}</td>
                    <td class="col-qty">{{ $pending->pending_qty ?? '' }}</td>
                </tr>
            </tbody>
            @endif

        </table>

        <x-footer />

    </div>

</div>
@endsection