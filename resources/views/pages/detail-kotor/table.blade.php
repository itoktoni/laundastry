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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $table)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox" name="code[]"
                                                value="{{ $table->field_primary }}">
                                        </td>
                                        <td class="col-md-2 text-center column-action">
                                            <x-crud :model="$table" />
                                        </td>

										<td >{{ $table->kotor_id }}</td>
										<td >{{ $table->kotor_code_scan }}</td>
										<td >{{ $table->kotor_code_packing }}</td>
										<td >{{ $table->kotor_code_bersih }}</td>
										<td >{{ $table->kotor_code_customer }}</td>
										<td >{{ $table->kotor_code_category }}</td>
										<td >{{ $table->kotor_id_jenis }}</td>
										<td >{{ $table->kotor_scan }}</td>
										<td >{{ $table->kotor_qc }}</td>
										<td >{{ $table->kotor_bersih }}</td>
										<td >{{ $table->kotor_pending }}</td>
										<td >{{ $table->kotor_tanggal }}</td>
										<td >{{ $table->kotor_created_at }}</td>
										<td >{{ $table->kotor_created_by }}</td>
										<td >{{ $table->kotor_updated_at }}</td>
										<td >{{ $table->kotor_updated_by }}</td>
										<td >{{ $table->kotor_deleted_at }}</td>
										<td >{{ $table->kotor_deleted_by }}</td>
										<td >{{ $table->kotor_qc_at }}</td>
										<td >{{ $table->kotor_qc_by }}</td>
										<td >{{ $table->kotor_bersih_at }}</td>
										<td >{{ $table->kotor_bersih_by }}</td>

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
