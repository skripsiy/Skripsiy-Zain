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
    protected $type; // 'assigned', 'solved', or 'inbox'

    public function __construct(User $user, $type = 'assigned')
    {
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Get tickets collection based on type
     */
    public function collection()
    {
        $query = Ticket::query();

        switch ($this->type) {
            case 'solved':
                $query->where(function($q) {
                    $q->where('solvedby', $this->user->email)->orWhere('solvedby', $this->user->name);
                });
                break;
            case 'inbox':
                $query->where(function($q) {
                    $q->where('assignby', $this->user->email)->orWhere('assignby', $this->user->name);
                })->where('status', 'QUEUED');
                break;
            case 'assigned':
            default:
                $query->where(function($q) {
                    $q->where('assignby', $this->user->email)->orWhere('assignby', $this->user->name);
                });
                break;
        }

        return $query->orderBy('datereport', 'desc')->get();
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
