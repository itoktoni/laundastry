<x-layout>

    <x-card class="table-container">

        <div class="col-md-12">

            <x-form method="GET" x-init="" x-target="table" role="search" aria-label="Contacts"
                autocomplete="off" action="{{ moduleRoute('getTable') }}">
                <x-filter toggle="Filter" :fields="$fields" />
            </x-form>

            <x-form method="POST" action="{{ moduleRoute('getTable') }}">

                <x-action />

                <div class="container-fluid" id="table">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="9" class="center">
                                        <input class="btn-check-d" type="checkbox">
                                    </th>
                                    <th class="text-center column-action">{{ __('Action') }}</th>
                                    <th>Kode</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th class="col-qty" style="width:10%;">Kotor</th>
                                    <th class="col-qty" style="width:10%;">QC</th>
                                    <th class="col-qty" style="width:10%;">Packing</th>
                                    <th class="col-qty" style="width:10%;">Pending</th>
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
                                            <x-crud :model="$table">
                                                <x-button module="getPrint" key="{{ $table->field_primary }}" color="dark" icon="printer"/>
                                                <x-button module="getQc" key="{{ $table->field_primary }}" color="warning" label="QC" icon="list"/>
                                                <x-button module="getPacking" key="{{ $table->field_primary }}" color="info" label="Packing"/>
                                                <x-button module="getPrintBersih" key="{{ $table->field_primary }}" color="success" label="Print Bersih"/>
                                            </x-crud>
                                        </td>

										<td data-label="ID">{{ $table->field_primary }}</td>
										<td data-label="Status">{{ $table->kotor_status }}</td>
										<td data-label="Tanggal">{{ $table->field_tanggal }}</td>
										<td data-label="Customer">{{ $table->customer_nama }}</td>
										<td data-label="Kotor">{{ $table->kotor_qty }}</td>
                                        <td data-label="QC" class="col-qty">{{ $table->kotor_qc }}</td>
						                <td data-label="Packing" class="col-qty">{{ $table->kotor_bersih }}</td>
						                <td data-label="Pending" class="col-qty">{{ $table->kotor_pending }}</td>

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
