<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProductQty;
use App\Http\Enumerations\Manage_Stock;
use App\Http\Enumerations\InStock;
use Illuminate\Validation\Rule;


class StockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sku' => 'nullable|min:3|max:10',
            'product_id' => 'required|exists:products,id',
            'manage_stock' => 'required|' . Rule::in([Manage_Stock::Available, Manage_Stock::NotAvailable]),
            'in_stock' => 'required|' . Rule::in([InStock::Available, InStock::NotAvailable]),
            //'qty' => 'required_if:manage_stock,==,' . Manage_Stock::Available , //first way
            'qty' => [new ProductQty($this->manage_stock)], // second way
        ];
    }
}
