<x-layout>

    <div class="row mb-2">
        <div class="col-lg-4 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="font-weight-bold mb-2">{{ $available }}</h2>
                            <div id="detail">STOCK AVAILABLE - {{ strtoupper($customer) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-lg-2 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="font-weight-bold mb-2">{{ $bersih }}</h2>
                            <div id="detail">BERSIH</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="font-weight-bold mb-2">{{ $kotor }}</h2>
                            <div>KOTOR</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="font-weight-bold mb-2">{{ $reject }}</h2>
                            <div>REJECT</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-lg-2 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="font-weight-bold mb-2">{{ $rewash }}</h2>
                            <div>REWASH</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

     <div class="row mb-2">
         <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="font-weight-bold mb-2">{{ $register }}</h2>
                            <div id="detail">TOTAL REGISTER LINEN</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="font-weight-bold mb-2">{{ $opname }}</h2>
                            <div id="detail">TOTAL OPNAME</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="font-weight-bold mb-2">{{ $pending_kotor }}</h2>
                            <div class="small">PENDING KOTOR</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-lg-2 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="font-weight-bold mb-2">{{ $pending_reject }}</h2>
                            <div class="small">PENDING REJECT</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-lg-2 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="font-weight-bold mb-2">{{ $pending_rewash }}</h2>
                            <div class="small">PENDING REWASH</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>

    </div>

    <style>
        .small{
            font-size: 0.7rem;
        }
    </style>

    @push('footer')
    <script src="{{ @asset('vendor/larapex-charts/apexcharts.js') }}"></script>
    {{ $chart->script() }}
    @endpush

</x-layout>
