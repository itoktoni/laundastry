<?php

namespace App\Livewire;

use App\Dao\Models\OpnameDetail;
use App\Dao\Models\Transaksi;
use Livewire\Component;

class UpdateOpname extends Component
{
    public $prefilledtransaksiID;
    public $transaksiID;
    public $opnameCode;

    public $qty;
    public $qtyRegister;
    public $status;
    public $message;

    public $id;

    public function mount($transaksiID = null, $id = null)
    {
        $this->prefilledtransaksiID = $transaksiID;
        $opname = OpnameDetail::where('odetail_id', $transaksiID)->first();
        $code = env('CODE_OPNAME', 'OPM').'-'.date('Ymd').unic(5).'_';

        if($opname)
        {
            $code = env('CODE_OPNAME', 'OPM').'-'.$opname->odetail_code_customer.'-'.date('Ymd').unic(5).'_';
            $this->status = null;
            $this->qty = intval($opname->odetail_ketemu);
            $this->qtyRegister = intval($opname->odetail_register);
        }

        $this->transaksiID = $transaksiID;
        $this->opnameCode = $code;

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

            if(intval($this->qty) == 0){
                $this->status = 'error';
                $this->message = 'Qty tidak boleh 0 !';
                return;
            }

            $result = OpnameDetail::where('odetail_id', $this->transaksiID)->update([
                'odetail_code' => $this->opnameCode.$this->id,
                'odetail_ketemu' => $this->qty,
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


        return redirect()->route('opname.getPrint', ['code' => $this->transaksiID]);
    }

    public function settransaksiID($transaksiID)
    {
        $this->transaksiID = $transaksiID;
        $this->message = null; // Clear any previous messages
        $this->status = null;
    }

    public function render()
    {
        return view('livewire.update-opname');
    }
}