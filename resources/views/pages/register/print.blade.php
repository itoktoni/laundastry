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
        <h1>REGISTER DATA</h1>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="invoice-info">
        <h2>Customer: {{ strtoupper($customer->customer_nama ?? '') }}</h2>
        <p>Tanggal: {{ formatDate($model->register_tanggal) }}</p>
        <p>Code: {{ $model->register_code ?? null }}</p>
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
                @forelse ($data as $item)

                <tr class="item">
                    <td class="col-no">{{ $loop->iteration }}</td>
                    <td class="col-name text-left">{{ $item->jenis_nama }}</td>
                    <td class="col-qty">{{ $item->register_qty }}</td>
                </tr>
                @empty

                @endforelse

            </tbody>
			<tfoot>
				<tr>
					<td colspan="2" style="text-align: right">Total</td>
					<td class="col-qty">{{ $data->sum('register_qty') }}</td>
				</tr>
			</tfoot>
        </table>

        <x-footer />

    </div>

</div>
@endsection