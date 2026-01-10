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
                                    @foreach ($fields as $value)
                                        <th {{ Template::extractColumn($value) }}>
                                            {{ __($value->name) }}
                                        </th>
                                    @endforeach
                                    <th>Customer</th>
                                    <th>Berat Kg</th>
                                    <th>Harga</th>
                                    @if (auth()->user()->role == RoleType::Developer)
                                    <th>Fee</th>
                                    @endif
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
                                            <x-crud :model="$table" />
                                        </td>

										<td data-label="ID">{{ $table->jenis_id }}</td>
										<td data-label="Nama">{{ $table->jenis_nama }}</td>
										<td data-label="Customer">{{ $table->customer_nama }}</td>
										<td data-label="Berat Kg">{{ $table->jenis_berat }}</td>
										<td data-label="Harga">{{ $table->jenis_harga }}</td>
                                        @if (auth()->user()->role == RoleType::Developer)
                                        <td data-label="Fee">{{ $table->jenis_fee }}</td>
                                        @endif
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
