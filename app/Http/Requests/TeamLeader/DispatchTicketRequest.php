<?php

namespace App\Http\Requests\TeamLeader;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DispatchTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $ticketId = $this->route('id') ?? $this->route('idTicket');
        $ticket = Ticket::where('idTicket', $ticketId)->firstOrFail();
        
        return $this->user()->can('dispatchToTeam', $ticket);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $klasifikasi = $this->input('klasifikasi');
        
        $validTeams = [];
        if (strtolower($klasifikasi) === 'technical') {
            $validTeams = array_keys(config('teams.technical'));
        } else {
            $validTeams = array_keys(config('teams.non_technical'));
        }

        return [
            'resume'         => 'required|string|max:2000',
            'klasifikasi'    => 'required|string|in:Technical,Non-Technical',
            'topic'          => 'required|string|max:255',
            'topicDetail'    => 'required|string|max:255',
            'noSC'           => 'required|string|max:100',
            'statusSC'       => 'nullable|string|max:50',
            'validateClose'  => 'required|string|max:255',
            'reasonnoODS'    => 'required|string|max:1000',
            'eksalasiTicket' => 'required|string|max:255',
            'eksalasiVia'    => 'required|string|max:255',
            'PIC'            => ['required', Rule::in($validTeams)],
            'contact'        => 'required|string|max:50',
            'responBE'       => 'required|string|max:2000',
            'description'    => 'required|string|max:5000',
            'attachment'     => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:10240',
        ];
    }

    /**
     * Get custom error messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'string'   => 'Kolom :attribute harus berupa teks.',
            'max'      => 'Kolom :attribute tidak boleh melebihi :max karakter.',
            'file'     => 'Kolom :attribute harus berupa file/berkas.',
            'mimes'    => 'Kolom :attribute harus berformat pdf, png, jpg, atau jpeg.',
            'attachment.max' => 'Ukuran file lampiran maksimal 10 MB.',
            'PIC.in'   => 'Tim terkait yang dipilih tidak valid.',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'resume'         => 'Resume',
            'klasifikasi'    => 'Klasifikasi',
            'topic'          => 'Topik',
            'topicDetail'    => 'Detail Topik',
            'noSC'           => 'No SC/Track ID',
            'statusSC'       => 'Status SC/Track ID',
            'validateClose'  => 'Validasi Close',
            'reasonnoODS'    => 'Reason Not ODS',
            'eksalasiTicket' => 'Eskalasi Tiket',
            'eksalasiVia'    => 'Eskalasi Via',
            'PIC'            => 'Tim Terkait',
            'contact'        => 'Kontak',
            'responBE'       => 'Respon Backend',
            'description'    => 'Deskripsi',
            'attachment'     => 'Lampiran',
        ];
    }
}
