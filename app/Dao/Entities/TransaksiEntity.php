<?php

namespace App\Dao\Entities;

use App\Dao\Models\Category;
use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;

trait TransaksiEntity
{
    public static function field_code()
    {
        return 'transaksi_code';
    }

    public function getFieldCodeAttribute()
    {
        return $this->{$this->field_code()};
    }

     public static function field_code_pending()
    {
        return 'transaksi_code_pending';
    }

    public function getFieldCodePendingAttribute()
    {
        return $this->{$this->field_code_pending()};
    }

    public static function field_code_scan()
    {
        return 'transaksi_code_scan';
    }

    public function getFieldCodeScanAttribute()
    {
        return $this->{$this->field_code_scan()};
    }

    public static function field_jenis_id()
    {
        return 'transaksi_id_jenis';
    }

    public function getFieldJenisIdAttribute()
    {
        return $this->{$this->field_jenis_id()};
    }

    public function getFieldJenisNameAttribute()
    {
        return $this->{Jenis::field_name()};
    }

    public static function field_category_id()
    {
        return 'transaksi_id_category';
    }

    public function getFieldCategoryIdAttribute()
    {
        return $this->{$this->field_category_id()};
    }

    public function getFieldCategoryNameAttribute()
    {
        return $this->{Category::field_name()};
    }

    public static function field_customer_code()
    {
        return 'transaksi_code_customer';
    }

    public function getFieldcustomerCodeAttribute()
    {
        return $this->{$this->field_customer_code()};
    }

    public function getFieldCustomerNameAttribute()
    {
        return $this->{Customer::field_name()};
    }

    public static function field_tanggal()
    {
        return 'transaksi_tanggal';
    }

    public function getFieldTanggalAttribute()
    {
        return $this->{$this->field_tanggal()};
    }

    public static function field_report()
    {
        return 'transaksi_report';
    }

    public function getFieldReportAttribute()
    {
        return $this->{$this->field_report()};
    }

    public static function field_status()
    {
        return 'transaksi_status';
    }

    public function getFieldStatusAttribute()
    {
        return $this->{$this->field_status()};
    }

    public static function field_scan()
    {
        return 'transaksi_scan';
    }

    public function getFieldScanAttribute()
    {
        return $this->{$this->field_scan()};
    }

    public static function field_qc()
    {
        return 'transaksi_qc';
    }

    public function getFieldQcAttribute()
    {
        return $this->{$this->field_qc()};
    }

    public static function field_bersih()
    {
        return 'transaksi_bersih';
    }

    public function getFieldBersihAttribute()
    {
        return $this->{$this->field_bersih()};
    }

    public static function field_pending()
    {
        return 'transaksi_pending';
    }

    public function getFieldPendingAttribute()
    {
        return $this->{$this->field_pending()};
    }
}
