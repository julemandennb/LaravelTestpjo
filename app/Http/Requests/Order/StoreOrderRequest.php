<?php

namespace App\Http\Requests\Order;

use App\Dto\Order\OrderData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enum\OrderStatus;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string|min:8|max:20',
            'address' => 'required|string',
            'postNr' => 'required|numeric|digits:4',
            'status' => ['required', Rule::enum(OrderStatus::class)],
            'products' => 'required|array',

            'products.*.id' => [
                'required',
                'integer',
                'exists:produkts,id',
            ],
        ];
    }

    public function dto():OrderData
    {
        return OrderData::from($this->validated());
    }

}
