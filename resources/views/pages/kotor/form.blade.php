<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

                @bind($model)

                <x-form-select col="4" name="customer_code" label="Customer" :options="$customer" />
                <x-form-select col="2" name="kotor_category" label="Category" :options="$category" />
                <x-form-input col="6" name="filter" label="Filter Jenis Linen" type="text" />

                <div class="container mt-3">

                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 4%" class="checkbox-column">No.</th>
                                <th style="width: 60%">Jenis Linen</th>
                                <th style="width: 10%" class="text-center">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jenis as $key => $value)
                                <tr>
                                    <input type="hidden" name="qty[{{ $key }}][kotor_id]"
                                        value="{{ null ?? null }}" />
                                    <td data-label="No." class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Linen">{{ $value }}</td>
                                    <td data-label="Qty">
                                        <input class="form-control" type="number" min="0" value="{{ null ?? null }}"
                                            name="qty[{{ $key }}][qty]" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                @endbind

        </x-card>
    </x-form>
</x-layout>
