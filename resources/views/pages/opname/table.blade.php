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
                                    <th style="width: 70px">Code</th>
                                    <th>Customer</th>
                                    <th>Detail</th>
                                    <th style="width: 85px">Status</th>
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
                                                <x-button module="getCapture" key="{{ $table->field_primary }}" color="warning" label="Capture" />
                                                @if(!empty($table->opname_capture))
                                                <x-button module="getOpname" key="{{ $table->field_primary }}" color="success" label="Opname" />
                                                @endif
                                            </x-crud>
                                        </td>

										<td data-label="Code">{{ $table->field_primary }}</td>
										<td data-label="Customer">{{ $table->customer_nama }}</td>
										<td data-label="Detail">
                                            Tgl buat : {{ formatDate($table->opname_created_at) }}
                                            <br>
                                            Tgl Mulai : {{ formatDate($table->opname_mulai) }}
                                            <br>
                                            Tgl Selesai : {{ formatDate($table->opname_selesai) }}
                                            <br>
                                            Tgl Capture : {{ formatDate($table->opname_capture) }}
                                        </td>
										<td data-label="Status">{{ OpnameType::getDescription($table->opname_status) }}</td>

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
