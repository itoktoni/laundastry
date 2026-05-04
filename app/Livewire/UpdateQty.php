<?php

namespace App\Livewire;

use App\Dao\Enums\TransactionType;
use App\Dao\Models\Packing;
use App\Dao\Models\Transaksi;
use Livewire\Component;

class UpdateQty extends Component
{
    public $prefilledKotorId;
    public $kotorId;
    public $packingCode;

    public $qty;
    public $qtyQc;
    public $qtyKotor;
    public $status;
    public $message;

    public $id;
    public $module;

    public function mount($kotorId = null, $id = null, $module = null)
    {
        $this->prefilledKotorId = $kotorId;
        $kotor =  Transaksi::where('transaksi_id', $kotorId)->first();
        $this->kotorId = $kotorId;
        $this->module = moduleCode();

        if($kotor->transaksi_status == TransactionType::KOTOR)
        {
            $code = env('CODE_KOTOR', 'KTR');
        }
        else if($kotor->transaksi_status == TransactionType::REJECT)
        {
            $code = env('CODE_REJECT', 'RJT');
        }
        else if($kotor->transaksi_status == TransactionType::REWASH)
        {
            $code = env('CODE_REWASH', 'RWS');
        }

        $code = 'PACK-'.$code.'-'.$kotor->transaksi_code_customer.'-'.unic(5);

        $this->packingCode = $code;

        $total = Packing::where('packing_id_transaksi', $kotorId)->sum('packing_qty');

        $this->status = null;
        $this->qtyKotor = intval($kotor->transaksi_scan);
        $this->qtyQc = intval($kotor->transaksi_qc);
        $this->qty = $this->qtyKotor - $total;
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

            $qty = Packing::where('packing_id_transaksi', $this->prefilledKotorId)->sum('packing_qty');
            $total = $qty + $this->qty;

            if(intval($total) > $this->qtyQc){
                $this->status = 'error';
                $this->message = 'Qty tidak boleh lebih besar dari Kotor Qty!';
                return;
            }

            // ========= multiple packing ================

            $result = Packing::create([
                'packing_code' => $this->packingCode,
                'packing_id_transaksi' => $this->prefilledKotorId,
                'packing_qty' => $this->qty
            ]);

            $total = Packing::where('packing_id_transaksi', $this->prefilledKotorId)->sum('packing_qty');

            $result = Transaksi::where('transaksi_id', $this->kotorId)->update([
                'transaksi_code_packing' => $this->packingCode,
                'transaksi_bersih' => $total,
                'transaksi_pending' => $this->qtyQc - $total,
                'transaksi_bersih_at' => now(),
                'transaksi_bersih_by' => auth()->user()->id,
            ]);

            //========================

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


        return redirect()->route($this->module.'.getPrintPackingDetail', ['code' => $this->packingCode]);
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