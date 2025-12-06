<?php

namespace App\Livewire;

use App\Dao\Models\DetailKotor;
use Livewire\Component;

class UpdateQty extends Component
{
    public $prefilledKotorId;
    public $kotorId;
    public $kotorCode;

    public $qty;
    public $qtyQc;
    public $qtyKotor;
    public $status;
    public $message;

    public $id;

    public function mount($kotorId = null, $id = null)
    {
        $this->prefilledKotorId = $kotorId;
        $kotor =  DetailKotor::where('kotor_id', $kotorId)->first();
        $this->kotorId = $kotorId;
        $this->kotorCode = $kotor->kotor_code_scan;

        $this->status = null;
        $this->qtyKotor = $kotor->kotor_kotor;
        $this->qtyQc = $kotor->kotor_qc;
        $this->qty = $kotor->kotor_bersih;
        $this->message = null;

        $this->id = $id;
    }

    public function updateQty()
    {
        $this->validate([
            'kotorId' => 'required|integer',
            'qty' => 'required|numeric|min:0',
        ]);

        try {
            $result = DetailKotor::where('kotor_id', $this->kotorId)->update([
                'kotor_code_packing' => $this->kotorCode.'_PACK_'.$this->id,
                'kotor_bersih' => $this->qty,
                'kotor_pending' => $this->qty - $this->qtyQc,
                'kotor_bersih_at' => now(),
                'kotor_bersih_by' => auth()->user()->id,
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


        return redirect()->route('kotor.getPrintPacking', ['code' => $this->kotorId]);
    }

    public function setKotorId($kotorId)
    {
        $this->kotorId = $kotorId;
        $this->message = null; // Clear any previous messages
        $this->status = null;
    }

    public function render()
    {
        return view('livewire.update-qty');
    }
}