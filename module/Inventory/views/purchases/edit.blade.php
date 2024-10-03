@extends('layouts.master')
@section('title', 'Invoice No: '.$purchase->invoice_no)



@section('content')
    <div class="row">
        <div class="col-12">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <h4 class="pl-2"><i class="far fa-edit"></i> @yield('title')</h4>

                <ul class="breadcrumb mb-1">
                    <li><a href="{{ route('home') }}"><i class="ace-icon far fa-home-lg-alt"></i></a></li>
                    <li><a class="text-muted" href="{{ route('inv.purchases.index') }}">Purchase</a></li>
                    <li>{{ $purchase->invoice_no }}</li>
                </ul>
            </div>

            <div class="widget-body">
                <div class="widget-main">

                    @include('partials._alert_message')


                    <!-- PURCHASE EDIT FORM -->
                    <form class="form-horizontal" action="{{ route('inv.purchases.update', $purchase->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')



                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group mb-2 width-100" style="width: 100%">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Supplier<span class="label-required">*</span>
                                    </span>
                                    <select name="supplier_id" id="supplier_id" tabindex="1" class="form-control select2" required style="width: 100%">
                                        @foreach($suppliers as $id => $name)
                                            <option value="{{ $id }}" {{ old('supplier_id', $purchase->supplier_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group mb-2 width-100" style="width: 100%">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Warehouse<span class="label-required">*</span>
                                    </span>
                                    <select name="warehouse_id" id="warehouse_id" data-placeholder="- Select -" tabindex="2" class="form-control select2" style="width: 100%" required>
                                        <option></option>
                                        @foreach($warehouses as $id => $name)
                                            <option value="{{ $id }}" {{ old('warehouse_id', $purchase->warehouse_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group mb-2 width-100" style="width: 100%">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Date<span class="label-required">*</span>
                                    </span>
                                    <input type="text" name="date" id="date" tabindex="3" class="form-control date-picker" value="{{ old('date', $purchase->date) }}" autocomplete="off" data-date-format="yyyy-mm-dd">
                                </div>
                            </div>
                        </div>


                        <!-- PURCHASE TABLE -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="purchaseTable">
                                        <thead>
                                            <tr class="table-header-bg">
                                                <th width="15%">Product</th>
                                                <th width="12%">Variation</th>
                                                <th width="8%">Sku</th>
                                                <th width="10%">Category</th>
                                                <th width="10%">Unit</th>
                                                <th width="5%">Lot</th>
                                                <th width="10%" class="text-center">Unit Cost</th>
                                                <th width="10%" class="text-center">Qty</th>
                                                <th width="10%" class="text-center">Expired</th>
                                                <th width="10%">Comment</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (old('product_id'))
                                                @foreach (old('product_id') ?? [] as $key => $value)
                                                    <tr>
                                                        @php
                                                            $product = \Module\Product\Models\Product::with('productVariations')->find(old('product_id')[$key]);
                                                            $isExists = in_array(old('purchase_detail_id')[$key], $purchase->purchaseDetails->pluck('id')->toArray()) ? true : false;
                                                        @endphp

                                                        <input type="hidden" name="purchase_detail_id[]" value="{{ old('purchase_detail_id')[$key] }}">
                                                        <input type="hidden" class="product_is_variation" value="{{ count($product->productVariations) > 0 ? 'true' : 'false' }}">

                                                        <th width="25%">
                                                            <select name="product_id[]" id="product_id" class="form-control products {{ $isExists == true ? '' : 'select2' }}" data-placeholder="- Select -" onchange="getProductVariations(this)" {{ $isExists == true ? 'readonly' : '' }} required>
                                                                <option></option>
                                                                @foreach ($products ?? [] as $product)
                                                                    <option value="{{ $product->id }}"
                                                                        data-category="{{ optional($product->category)->name }}"
                                                                        data-unit-measure="{{ optional($product->unitMeasure)->name }}"
                                                                        data-purchase-price="{{ number_format($product->purchase_price, 2, '.', '') }}"
                                                                        {{ old('product_id')[$key] == $product->id ? 'selected' : '' }}
                                                                    >{{ $product->name . ' - ' . $product->code }}</option>
                                                                @endforeach
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <select name="product_variation_id[]" id="product_variation_id" class="form-control product-variations {{ $isExists == true ? '' : 'select2' }}" onchange="checkItemExistOrNot(this)"{{ $isExists == true ? 'readonly' : '' }} >
                                                                <option value="" selected>- Select -</option>
                                                                @php $product = \Module\Product\Models\Product::with('productVariations')->find(old('product_id')[$key]); @endphp
                                                                @foreach ($product->productVariations as $variation)
                                                                    <option value="{{ $variation->id }}" {{ old('product_variation_id')[$key] == $variation->id ? 'selected' : '' }}>{{ $variation->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <input type="text" name="" id="sku[]" value="{{ old('sku')[$key] }}" class="form-control sku-code" readonly>
                                                        </th>
                                                        <th>
                                                            <input type="text" name="category_id[]" id="category_id" class="form-control category" value="{{ old('category_id')[$key] }}" readonly>
                                                        </th>
                                                        <th>
                                                            <input type="text" name="unit_measure_id[]" id="unit_measure_id" class="form-control unit-measure" value="{{ old('unit_measure_id')[$key] }}" readonly>
                                                        </th>
                                                        <th>
                                                            <input type="text" name="lot[]" id="lot" class="form-control lot" value="{{ old('lot')[$key] }}" autocomplete="off">
                                                        </th>
                                                        <th>
                                                            <input type="text" name="purchase_price[]" id="purchase_price" class="form-control text-right only-number purchase-price" value="{{ old('purchase_price')[$key] }}" autocomplete="off" required>
                                                        </th>
                                                        <th>
                                                            <input type="number" name="quantity[]" id="quantity" class="form-control text-center quantity" value="{{ old('quantity')[$key] }}" autocomplete="off" required>
                                                        </th>
                                                        <th>
                                                            <input type="text" name="expired_dates[]" data-date-format="yyyy-mm-dd" class="form-control date-picker" readonly value="{{ old('expired_dates')[$key] }}" autocomplete="off">
                                                        </th>
                                                        <th>
                                                            <input type="text" name="special_comment[]" id="special_comment" class="form-control special_comment" value="{{ old('special_comment')[$key] }}" autocomplete="off">
                                                        </th>
                                                        <th class="text-center">
                                                            @if ($isExists == true)
                                                            <button type="button" class="btn btn-sm btn-danger" title="Delete" onclick="delete_item('{{ route('inv.purchases.items.destroy', old('purchase_detail_id')[$key]) }}')" {{ $loop->first ? 'disabled' : '' }}><i class="fa fa-trash"></i></button>
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-danger remove-row" title="Remove" {{ $loop->first ? 'disabled' : '' }}><i class="fa fa-times"></i></button>
                                                            @endif
                                                        </th>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach ($purchase->purchaseDetails as $purchaseDetail)
                                                    <tr>
                                                        <input type="hidden" name="purchase_detail_id[]" value="{{ $purchaseDetail->id }}">
                                                        <th>
                                                            <input type="hidden" class="product_is_variation" value="{{ $purchaseDetail->product_variation_id != null ? 'true' : 'false' }}">
                                                            <select name="product_id[]" id="product_id" class="form-control products" data-placeholder="- Select -" readonly required>
                                                                <option value="{{ $purchaseDetail->product_id }}">{{ optional($purchaseDetail->product)->name }} - {{ optional($purchaseDetail->product)->code }}</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <select name="product_variation_id[]" id="product_variation_id" class="form-control product-variations" data-placeholder="- Select -" readonly>
                                                                <option value="{{ $purchaseDetail->product_variation_id }}">{{ optional($purchaseDetail->productVariation)->name }}</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <input type="text" name="" id="sku[]" value="{{ optional($purchaseDetail->product)->sku }}" class="form-control sku-code" readonly>
                                                        </th>
                                                        <th>
                                                            <input type="text" name="category_id[]" id="category_id" class="form-control category" value="{{ optional(optional($purchaseDetail->product)->category)->name }}" readonly>
                                                        </th>
                                                        <th>
                                                            <input type="text" name="unit_measure_id[]" id="unit_measure_id" class="form-control unit-measure" value="{{ optional(optional($purchaseDetail->product)->unitMeasure)->name }}" readonly>
                                                        </th>
                                                        <th>
                                                            <input type="text" name="lot[]" id="lot" class="form-control lot" value="{{ $purchaseDetail->lot }}" autocomplete="off">
                                                        </th>
                                                        <th>
                                                            <input type="text" name="purchase_price[]" id="purchase_price" class="form-control text-right only-number purchase-price" value="{{ number_format($purchaseDetail->purchase_price, 2, '.', '') }}" autocomplete="off" required>
                                                        </th>
                                                        <th>
                                                            <input type="number" name="quantity[]" id="quantity" class="form-control text-center quantity" value="{{ number_format($purchaseDetail->quantity, 2, '.', '') }}" autocomplete="off" required>
                                                        </th>
                                                        <th>
                                                            <input type="text" name="expired_dates[]" data-date-format="yyyy-mm-dd" class="form-control date-picker" readonly value="{{ $purchaseDetail->expired_date }}" autocomplete="off">
                                                        </th>
                                                        <th>
                                                            <input type="text" name="special_comment[]" id="special_comment" class="form-control special_comment" value="{{ $purchaseDetail->special_comment }}" autocomplete="off">
                                                        </th>
                                                        <th>
                                                            <button type="button" class="btn btn-sm btn-danger" title="Delete" onclick="delete_item('{{ route('inv.purchases.items.destroy', $purchaseDetail->id) }}')" {{ $loop->first ? 'disabled' : '' }}><i class="fa fa-trash"></i></button>
                                                        </th>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9" class="text-center">
                                                    <a href="javascript:void(0)" type="button" class="btn btn-xs btn-block btn-light" style="color: #0084db !important" id="addrow">
                                                        <i class="fas fa-plus-circle"></i> ADD MORE
                                                    </a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="btn-group" style="float: right">
                            <button class="btn btn-sm btn-success"> <i class="far fa-edit"></i> UPDATE </button>
                            <a class="btn btn-sm btn-info" href="{{ route('inv.purchases.index') }}"> <i class="fa fa-bars"></i> LIST </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection





@section('script')
    @include('purchases._inc.script')
@endsection
