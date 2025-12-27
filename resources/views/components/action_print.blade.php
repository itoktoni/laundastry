
@if(request()->get('action') == 'excel')
    @php
        $name = request()->get('report_name') ? request()->get('report_name').'.xls' : moduleName().'.xls';
        header('Content-Type: application/force-download');
        header('Content-disposition: attachment; filename='.$name);
        header("Pragma: ");
        header("Cache-Control: ");
    @endphp
@else
    <div class="header-action">
        <nav>
            <a href="{{ url()->previous() }}">Back</a>
            <a class="cursor" onclick="window.print()">Print</a>
            <a href="{{ url()->full().'&action=excel' }}">Excel</a>
        </nav>
    </div>
@endif

<style>
    @media (max-width: 768px) {
        .header-action{
            height: 100px;
        }

        .header-action nav {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            padding: 20px;
        }

        .header-action nav a {
            text-decoration: none;
            color: #000;
            font-size: 2rem;
            padding: 1rem 2rem;
            font-weight: bold;
        }
    }
</style>
