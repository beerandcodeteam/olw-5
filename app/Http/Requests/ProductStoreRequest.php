<?php

namespace App\Http\Requests;

use App\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role_id == RoleEnum::ADMIN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand_id' => 'required',
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'sku.*.name' => 'required|string|max:255',
            'sku.*.price' => 'required|decimal:2',
            'sku.*.quantity' => 'required|integer|min:1',
            'sku.*.images.*.url' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
