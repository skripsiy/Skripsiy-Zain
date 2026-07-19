<?php

namespace App\Exports;

use App\Models\Ticket;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UserTicketsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, ShouldAutoSize
{
    protected $user;
    protected $type; // 'assigned', 'solved', 'inbox', or 'dispatched'
    protected $filters;

    public function __construct(User $user, $type = 'assigned', array $filters = [])
    {
        $this->user = $user;
        $this->type = $type;
        $this->filters = $filters;
    }

    /**
     * Get tickets collection based on type
     */
    public function collection()
    {
        $query = Ticket::query();

        $rangeFilters = [
            'date_from' => $this->filters['date_from'] ?? null,
            'date_to' => $this->filters['date_to'] ?? null,
        ];

        switch ($this->type) {
            case 'solved':
                $query->where('solved_by_user_id', $this->user->id);
                $query->whereBetween('datesolved', [$rangeFilters['date_from'] ?? null, $rangeFilters['date_to'] ?? null]);
                break;
            case 'inbox':
                $query->where('assigned_to_user_id', $this->user->id)
                    ->where('condition', 'QUEUED');
                break;
            case 'dispatched':
                $query->where('assigned_to_user_id', $this->user->id)
                    ->where('condition', 'Dispatched');
                break;
            case 'assigned':
            default:
                $query->where('assigned_to_user_id', $this->user->id)
                    ->whereNotIn('condition', ['Dispatched', 'Closed']);
                break;
        }

        if (!empty($rangeFilters['date_from']) || !empty($rangeFilters['date_to'])) {
            $query->where(function ($range) use ($rangeFilters) {
                $range->where(function ($createdRange) use ($rangeFilters) {
                    if (!empty($rangeFilters['date_from'])) {
                        $createdRange->whereDate('created_at', '>=', $rangeFilters['date_from']);
                    }
                    if (!empty($rangeFilters['date_to'])) {
                        $createdRange->whereDate('created_at', '<=', $rangeFilters['date_to']);
                    }
                })->orWhere(function ($updatedRange) use ($rangeFilters) {
                    if (!empty($rangeFilters['date_from'])) {
                        $updatedRange->whereDate('updated_at', '>=', $rangeFilters['date_from']);
                    }
                    if (!empty($rangeFilters['date_to'])) {
                        $updatedRange->whereDate('updated_at', '<=', $rangeFilters['date_to']);
                    }
                });
            });
        }

        return $query->orderBy('updated_at', 'desc')->get();
    }

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            'Ticket ID',
            'Date Report',
            'Customer Name',
            'Customer Phone',
            'Jenis Ticket',
            'Detail',
            'Priority',
            'Status',
            'Regional',
            'Witel',
            'Resume',
            'Klasifikasi',
            'Topic',
            'Date Solved',
            'Created At',
        ];
    }

    /**
     * Map each row data
     */
    public function map($ticket): array
    {
        return [
            $ticket->idTicket,
            $ticket->datereport ? \Carbon\Carbon::parse($ticket->datereport)->format('Y-m-d') : '',
            $ticket->namacust,
            $ticket->notelpCust,
            $ticket->jenisTicket,
            $ticket->detailticket,
            $ticket->reportedpriority,
            $ticket->status,
            $ticket->regional,
            $ticket->witel,
            $ticket->resume,
            $ticket->klasifikasi,
            $ticket->topic,
            $ticket->datesolved ? \Carbon\Carbon::parse($ticket->datesolved)->format('Y-m-d') : '',
            $ticket->created_at ? $ticket->created_at->format('Y-m-d H:i:s') : '',
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headings)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2C5F7C']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]
        ];
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 12,  // Ticket ID
            'B' => 15,  // Date Report
            'C' => 25,  // Customer Name
            'D' => 18,  // Customer Phone
            'E' => 20,  // Jenis Ticket
            'F' => 35,  // Detail
            'G' => 18,  // Priority
            'H' => 15,  // Status
            'I' => 15,  // Regional
            'J' => 15,  // Witel
            'K' => 30,  // Resume
            'L' => 20,  // Klasifikasi
            'M' => 20,  // Topic
            'N' => 15,  // Date Solved
            'O' => 20,  // Created At
        ];
    }

    /**
     * Sheet title
     */
    public function title(): string
    {
        return ucfirst($this->type) . ' Tickets';
    }
}
