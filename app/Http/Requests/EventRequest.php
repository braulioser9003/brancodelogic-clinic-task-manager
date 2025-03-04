<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'string',
            'date_start' => 'required|date',
            'date_end' => 'date|after_or_equal:start',
            'location' => 'string|max:255',
            'type_reminder' => 'required|string|in:Email,SMS',
            'user_id' => 'required|integer',
            'type_event' => 'required|string|in:Surgery,Appointment,Follow Up',
            'category' => 'required|string|in:bg-danger,bg-success,bg-primary,bg-info,bg-dark,bg-warning',
        ];
    }
}
