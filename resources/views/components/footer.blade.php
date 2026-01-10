<h5 style="text-align: right;margin-bottom:5px">{{ date('d F Y') }}</h5>
<table>
    <tr>
        <td style="padding-top: 5rem;"></td>
        <td style="padding-top: 5rem;"></td>
    </tr>
    <tr>
        <td style="width:50%;text-align:center">{{ auth()->user()->name ?? '' }}</td>
        <td style="width:50%;text-align:center">Penerima</td>
    </tr>
</table>

<p style="font-size: 8px;margin-top:2rem">
    *Apabila terjadi perbedaan maka harus bersatu
    .
</p>