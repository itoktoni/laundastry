<?php

namespace App\Livewire;

use App\Dao\Models\Transaksi;
use Livewire\Component;

class UpdatePending extends Component
{
    public $prefilledtransaksiID;
    public $transaksiID;
    public $pendingCode;

    public $qty;
    public $qtyPending;
    public $status;
    public $message;

    public $id;

    public function mount($transaksiID = null, $id = null)
    {
        $this->prefilledtransaksiID = $transaksiID;
        $kotor =  Transaksi::where('transaksi_id', $transaksiID)->first();
        $this->transaksiID = $transaksiID;

        if($kotor)
        {
            $code = env('CODE_PENDING', 'PND').'-'.$kotor->transaksi_code_customer.'-'.date('Ymd').unic(5).'_';
        }
        else
        {
            $code = env('CODE_PENDING', 'PND').'-'.date('Ymd').unic(5).'_';
        }

        $this->pendingCode = $code;

        $this->status = null;
        $this->qtyPending = $kotor ? intval($kotor->transaksi_pending) : 0;
        $this->qty = $kotor ? intval($kotor->transaksi_bayar) : 0;
        $this->message = null;

        $this->id = $id;
    }

    public function updateQty()
    {
        $this->validate([
            'transaksiID' => 'required|integer',
            'qty' => 'required|numeric|min:0',
        ]);

        try {

            if(intval($this->qty) > $this->qtyPending){
                $this->status = 'error';
                $this->message = 'Qty tidak boleh lebih besar dari Pending!';
                return;
            }

            $result = Transaksi::where('transaksi_id', $this->transaksiID)->update([
                'transaksi_code_pending' => $this->pendingCode.$this->id,
                'transaksi_bayar' => $this->qty,
                'transaksi_pending_at' => now(),
                'transaksi_pending_by' => auth()->user()->id,
            ]);

            if ($result) {
                $this->status = 'success';
                $this->message = 'Qty berhasil diupdate!';

            } else {
                $this->status = 'error';
                $this->message = 'Kotor ID tidak ditemukan!';
            }
        } catch (\Exception $e) {

            abort(500, $e->getMessage());
            $this->status = 'error';
            $this->message = 'Error: ' . $e->getMessage();
        }


        return redirect()->route('pending.getPrint', ['code' => $this->transaksiID]);
    }

    public function settransaksiID($transaksiID)
    {
        $this->transaksiID = $transaksiID;
        $this->message = null; // Clear any previous messages
        $this->status = null;
    }

    public function render()
    {
        return view('livewire.update-pending');
    }
}