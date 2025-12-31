<x-layout>

    <x-card class="table-container">

        <div class="col-md-12">

            <x-form method="GET" x-init="" x-target="table" role="search" aria-label="Contacts"
                autocomplete="off" action="{{ moduleRoute('getTable') }}">

                <div class="container-fluid filter-container mb-2">
                    <div class="row">

                        <x-form-input type="date" col="3" label="Start Tanggal Pending" name="start_date" />
                        <x-form-input type="date" col="3" label="End Tanggal Pending" name="end_date" />
                        <x-form-select col="6" name="customer" label="Customer" :options="$customer" />

                    </div>
                </div>

                <x-filter toggle="Filter" :fields="$fields" />
            </x-form>

            <x-form method="POST" action="{{ moduleRoute('getTable') }}">

                <x-action form="blank">
                    <x-button :module="ACTION_CREATE" color="success" label="Buat" />
                </x-action>

                <div class="container-fluid" id="table">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="9" class="center">
                                        <input class="btn-check-d" type="checkbox">
                                    </th>
                                    <th class="text-center column-action">{{ __('Action') }}</th>
                                    <th style="width: 80px" >Code</th>
                                    <th>Customer</th>
                                    <th>Jenis Linen</th>
                                    <th class="text-right">Pending</th>
                                    <th class="text-right">Pembayaran</th>
                                    <th class="col-qty text-center" style="width:70px;">Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $table)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox" name="code[]"
                                                value="{{ $table->field_primary }}">
                                        </td>
                                        <td data-label="Action" class="col-md-2 text-center column-action">

                                            <div class="action-table">
                                                <x-button module="getUpdate" key="{{ $table->field_primary }}" color="success" label="Bayar" />
                                                <x-button module="getPrint" key="{{ $table->field_primary }}" color="dark" label="Cetak"/>
                                            </div>

                                        </td>

										<td data-label="Code">{{ $table->field_primary }}</td>
										<td data-label="Customer">
                                            {{ $table->customer_nama }}
                                        </td>
                                        <td data-label="Jenis Linen">
                                            {{ $table->jenis_nama }}
                                        </td>

										<td class="text-right" data-label="Pending">
                                            <div class="col-md-12">
                                                {{ formatDate($table->transaksi_bersih_at) }}
                                                <br>
                                                Qty : <b>{{ $table->field_pending }}</b>
                                            </div>
                                        </td>
										<td class="text-right" data-label="Pembayaran">
                                            <div class="col-md-12">
                                                @if ($table->transaksi_bayar > 0)
                                                {{ formatDate($table->transaksi_pending_at) }}
                                                <br>
                                                Qty : <b>{{ $table->transaksi_bayar }}</b>
                                                @endif
                                            </div>
                                        </td>
										<td class="text-center" data-label="Outstanding">{{ $table->field_pending - $table->transaksi_bayar }}</td>

                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <x-pagination :data="$data" />
                </div>

            </x-form>

        </div>

    </x-card>

</x-layout>
