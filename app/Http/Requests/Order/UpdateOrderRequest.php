<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use App\Dto\Order\OrderData;
use Illuminate\Validation\Rule;
use App\Enum\OrderStatus;

class UpdateOrderRequest extends FormRequest
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
            'products' => 'nullable|array', // Ensure each product ID is valid

            'products.*.id' => [
                'nullable',
                'integer',
            ],
            'products.*.produktID' => [
                'nullable',
                'integer',
                'exists:produkts,id',
            ],
            'products.*.quantity' => [
                'nullable',
                'integer',

            ]
        ];
    }

    public function dto():OrderData
    {
        return OrderData::from($this->validated());
    }
}
