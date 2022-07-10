<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStatRequest extends FormRequest
{
    use DefaultRequest;

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
            "stats"     => ["required", "array", "min:1"],
            "player_id" => ["required", "exists:players,id"]
        ];
    }

    public function attributes()
    {
        return [
            "statis"    => "statistics",
            "player_id" => "player"
        ];
    }
}
