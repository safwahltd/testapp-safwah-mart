<?php


namespace Module\Account\Models;

use Module\PosErp\Models\SaleReturn;

class SaleReturnDetail extends Model
{
    protected $table = 'acc_sale_return_details';

    public function sale_return()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id', 'id');
    }

    public function sale_detail()
    {
        return $this->belongsTo(SaleDetail::class, 'sale_detail_id', 'id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }



    public function pos_stocks()
    {

        return $this->hasMany(ProductStockDetail::class, 'source_id', 'id')->where('type', 'Account Sale Return');
    }
}
