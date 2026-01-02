<x-layout>

    <x-card class="table-container">

        <div class="col-md-12">
            <x-form method="GET" x-init="" x-target="table" role="search" aria-label="Contacts"
                autocomplete="off" action="{{ moduleRoute('getTable') }}">

                <div class="container-fluid filter-container mb-2">
                    <div class="row">

                        <x-form-input type="date" col="3" label="Start Date" name="start_date" />
                        <x-form-input type="date" col="3" label="End Date" name="end_date" />
                        <x-form-select col="2" name="category_code" label="Category" :options="$category" />
                        <x-form-select col="4" name="customer_code" label="Customer" :options="$customer" />

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
                                    <th class="text-center" style="width: 120px">Kode</th>
                                    <th>Customer</th>
                                    <th class="text-center" style="width: 120px">Category</th>
                                    <th class="col-qty text-center" style="width:80px;">Kotor</th>
                                    <th class="col-qty text-center" style="width:60px;">QC</th>
                                    <th class="col-qty text-center" style="width:90px;">Packing</th>
                                    <th class="col-qty text-center" style="width:90px;">Pending</th>
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
                                                @if (empty($table->field_code_bersih))
                                                    <x-button module="getUpdate" key="{{ $table->field_primary }}"
                                                        color="primary" icon="pencil-square" />
                                                    <x-button module="getDelete" key="{{ $table->field_primary }}"
                                                        color="danger" icon="trash3"
                                                        onclick="return confirm('Apakah anda yakin ingin menghapus ?')"
                                                        class="button-delete" />
                                                @endif
                                                <x-button module="getPrintKotor" key="{{ $table->field_primary }}"
                                                    color="dark" icon="printer" />

                                                @if (empty($table->field_code_bersih))
                                                    <x-button module="getQc" key="{{ $table->field_primary }}"
                                                        color="warning" label="QC" icon="list" />
                                                @endif

                                                <x-button module="getPacking" key="{{ $table->field_primary }}"
                                                    color="info" label="Packing" />
                                                <x-button module="getPrintBersih" key="{{ $table->field_primary }}"
                                                    color="success" label="Print DO" />
                                            </div>
                                        </td>

                                        <td class="text-center" data-label="Kode">
                                            {{ $table->field_primary }}
                                            <br>
                                            <br>
                                            {{ formatDate($table->field_tanggal) }}
                                        </td>
                                        <td data-label="Customer">
                                            {{ $table->customer_nama }}
                                        </td>
                                         <td class="text-center" data-label="Category">
                                            <span class="btn btn-block" style="color:white;background-color: {{ $table->category_warna ?? '' }}">{{ $table->category_nama ?? '' }}</span>
                                        </td>
                                        <td class="text-center" data-label="Kotor">{{ $table->field_scan }}</td>
                                        <td class="text-center" data-label="QC">{{ $table->field_qc }}</td>
                                        <td class="text-center" data-label="Packing">{{ $table->field_bersih }}</td>
                                        <td class="text-center" data-label="Pending">{{ $table->field_pending }}</td>

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
