<h5 style="text-align: right;margin-bottom:5px">{{ date('d F Y') }}</h5>
<table>
    <tr>
        <td>
            <br><br><br><br>
        </td>
        <td>
            <br><br><br><br>
        </td>
    </tr>
    <tr>
        <td style="width:50%;text-align:center">{{ auth()->user()->name ?? '' }}</td>
        <td style="width:50%;text-align:center">Penerima</td>
    </tr>
</table>

<p style="font-size: 8px;margin-top:2rem">
    *Apabila terdapat perbedaan dalam perhitungan yang dianggap benar adalah berdasarkan fisik linen yang dihitung saat sortir.
</p>

<span style="font-size: 5px;text-align:center">.</span>