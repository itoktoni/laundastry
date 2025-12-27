
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
