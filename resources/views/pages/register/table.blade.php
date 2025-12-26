<x-layout>

    <x-card class="table-container">

        <div class="col-md-12">

            <x-form method="GET" x-init="" x-target="table" role="search" aria-label="Contacts"
                autocomplete="off" action="{{ moduleRoute('getTable') }}">

                <div class="container-fluid filter-container mb-2">
                    <div class="row">

                        <x-form-input type="date" col="3" label="Start Date" name="start_date" />
                        <x-form-input type="date" col="3" label="End Date" name="end_date" />
                        <x-form-select col="6" name="customer_code" label="Customer" :options="$customer" />

                    </div>
                </div>

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
                                    <th>Code</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th class="col-qty" style="width:10%;">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $table)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox" name="code[]"
                                                value="{{ $table->register_code }}">
                                        </td>
                                        <td class="col-md-2 text-center column-action">

                                            <div class="action-table">
                                                <x-button module="getUpdate" key="{{ $table->register_code }}" color="primary" icon="pencil-square" />
                                                <x-button module="getDelete" key="{{ $table->register_code }}" color="danger" icon="trash3"  hx-confirm="Apakah anda yakin ingin menghapus ?" class="button-delete" />
                                                <x-button module="getPrint" key="{{ $table->register_code }}" color="dark" icon="printer"/>
                                            </div>

                                        </td>

										<td >{{ $table->register_code }}</td>
										<td >{{ formatDate($table->register_tanggal) }}</td>
										<td >{{ $table->customer_nama }}</td>
										<td >{{ $table->total_qty }}</td>

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
