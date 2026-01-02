<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class TicketIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // policy akan dipanggil di controller
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'in:OPEN,IN_PROGRESS,WAITING_CUSTOMER,RESOLVED,CLOSED'],
            'priority' => ['sometimes', 'in:low,medium,high,urgent'],
            'assigned_to' => ['sometimes', 'string'], // me|unassigned|{id}
            'search' => ['sometimes', 'string', 'max:200'],
            'sort' => ['sometimes', 'in:created_at,-created_at,updated_at,-updated_at,priority,-priority,status,-status,number,-number'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
