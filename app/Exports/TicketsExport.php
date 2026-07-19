<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TicketsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, ShouldAutoSize
{
    protected $filters;
    protected $tickets;

    public function __construct($filters = [], $tickets = null)
    {
        $this->filters = $filters;
        $this->tickets = $tickets;
    }

    /**
     * Get tickets collection based on filters
     */
    public function collection()
    {
        if ($this->tickets !== null) {
            return $this->tickets;
        }
        $query = Ticket::query()->with(['assignedTo', 'solvedBy']);

        $filters = array_filter($this->filters, function ($value) {
            return $value !== null && $value !== '';
        });
        $query->reportFilter($filters);

        if (!empty($this->filters['priority'])) {
            $query->where('reportedpriority', $this->filters['priority']);
        }

        if (!empty($this->filters['regional'])) {
            $query->where('regional', $this->filters['regional']);
        }

        if (!empty($this->filters['witel'])) {
            $query->where('witel', $this->filters['witel']);
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
            'Jenis Ticket',
            'Customer Phone',
            'Customer Name',
            'ID Laporan',
            'Detail Ticket',
            'Priority',
            'Status',
            'Regional',
            'Witel',
            'GAMAS',
            'LAPUL',
            'GAUL',
            'Resume',
            'Klasifikasi',
            'Topic',
            'Topic Detail',
            'No SC',
            'Status SC',
            'Validate Close',
            'Reason No ODS',
            'Eskalasi Ticket',
            'Eskalasi Via',
            'PIC',
            'Contact',
            'Respon BE',
            'Description',
            'Date Solved',
            'THT',
            'Condition',
            'Assigned By',
            'Solved By',
            'Escalation Status',
            'Created At',
            'Updated At'
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
            $ticket->jenisTicket,
            $ticket->notelpCust,
            $ticket->namacust,
            $ticket->idlaporan,
            $ticket->detailticket,
            $ticket->reportedpriority,
            $ticket->status,
            $ticket->regional,
            $ticket->witel,
            $ticket->gamas,
            $ticket->lapul,
            $ticket->gaul,
            $ticket->resume,
            $ticket->klasifikasi,
            $ticket->topic,
            $ticket->topicDetail,
            $ticket->noSC,
            $ticket->statusSC,
            $ticket->validateClose,
            $ticket->reasonnoODS,
            $ticket->eksalasiTicket,
            $ticket->eksalasiVia,
            $ticket->PIC,
            $ticket->contact,
            $ticket->responBE,
            $ticket->description,
            $ticket->datesolved ? \Carbon\Carbon::parse($ticket->datesolved)->format('Y-m-d') : '',
            $ticket->THT ? \Carbon\Carbon::parse($ticket->THT)->format('Y-m-d H:i:s') : '',
            $ticket->condition,
            $ticket->assignedTo->name ?? '',
            $ticket->solvedBy->name ?? '',
            $ticket->escalationStatus,
            $ticket->created_at ? $ticket->created_at->format('Y-m-d H:i:s') : '',
            $ticket->updated_at ? $ticket->updated_at->format('Y-m-d H:i:s') : ''
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
            'C' => 20,  // Jenis Ticket
            'D' => 18,  // Customer Phone
            'E' => 25,  // Customer Name
            'F' => 18,  // ID Laporan
            'G' => 35,  // Detail Ticket
            'H' => 18,  // Priority
            'I' => 15,  // Status
            'J' => 15,  // Regional
            'K' => 15,  // Witel
        ];
    }
}
