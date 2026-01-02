<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class TicketUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'priority' => ['sometimes', 'in:low,medium,high,urgent'],
            'status' => ['sometimes', 'in:OPEN,IN_PROGRESS,WAITING_CUSTOMER,RESOLVED,CLOSED'],
            'requester_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'requester_email' => ['sometimes', 'nullable', 'email', 'max:255'],
        ];
    }
}
