<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

                @bind($model)

                <input type="hidden" name="register_code" value="{{ $model->register_code ?? null }}" />

                <x-form-select col="4" id="customer" default="{{ request()->get('customer') ?? $model->register_code_customer ?? null }}" name="customer_code" label="Customer" :options="$customer" />
                <x-form-select col="2" name="kotor_status" default="{{ $model->kotor_code_category ?? 'REGISTER' }}" label="Category" :options="$category" />
                <div class=" form-group col-md-6 ">
                    <label for="auto_id_filter">Filter Jenis Linen</label>
                    <input class="form-control search" type="text" value="" name="filter" id="auto_id_filter">
                </div>

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
                            @php

                            $qty = 0;
                            $register_id = null;

                            if(isset($detail)){
                                $register = $detail->firstWhere('register_id_jenis', $key);
                                $qty = $register ? $register->register_qty : 0;
                                $register_id = $register ? $register->register_id : null;
                            }

                            @endphp

                                <tr>

                                    <input type="hidden" name="qty[{{ $key }}][jenis_id]"
                                        value="{{ $key ?? null }}" />

                                    <td data-label="No." class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Linen">{{ $value }}</td>
                                    <td data-label="Qty">
                                        <input class="form-control text-right" type="number" min="0" value="{{ $qty ?? null }}"
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const customerSelect = document.getElementById('customer');
            if (customerSelect) {
                customerSelect.addEventListener('change', function() {
                    const selectedValue = this.value;
                    const currentUrl = new URL(window.location);

                    if (selectedValue) {
                        currentUrl.searchParams.set('customer', selectedValue);
                    } else {
                        currentUrl.searchParams.delete('customer');
                    }

                    window.location.href = currentUrl.toString();
                });
            }


            const searchInput = document.querySelector('.search');
            const tableRows = document.querySelectorAll('.table tbody tr');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();

                    tableRows.forEach(function(row) {
                        const linenCell = row.querySelector('td[data-label="Linen"]');
                        if (linenCell) {
                            const linenText = linenCell.textContent.toLowerCase().trim();
                            if (searchTerm === '' || linenText.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                });
            }
        });
    </script>
</x-layout>
