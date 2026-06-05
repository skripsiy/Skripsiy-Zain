<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

class UserReportsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, ShouldAutoSize, WithEvents
{
    /**
     * Get users collection with ticket statistics
     */
    public function collection()
    {
        return User::select('users.*')
            ->selectRaw('COUNT(DISTINCT CASE WHEN (tickets.assignby = users.email OR tickets.assignby = users.name) THEN tickets.idTicket END) as assigned_tickets')
            ->selectRaw('COUNT(DISTINCT CASE WHEN (tickets.solvedby = users.email OR tickets.solvedby = users.name) THEN tickets.idTicket END) as solved_tickets')
            ->selectRaw('COUNT(DISTINCT CASE WHEN (tickets.assignby = users.email OR tickets.assignby = users.name) AND tickets.status = "QUEUED" THEN tickets.idTicket END) as inbox_tickets')
            ->leftJoin('tickets', function($join) {
                $join->on('tickets.assignby', '=', 'users.email')
                     ->orOn('tickets.assignby', '=', 'users.name')
                     ->orOn('tickets.solvedby', '=', 'users.email')
                     ->orOn('tickets.solvedby', '=', 'users.name');
            })
            ->groupBy('users.id', 'users.name', 'users.email', 'users.password', 'users.role', 'users.status', 'users.campaign', 'users.area', 'users.site', 'users.username', 'users.phone', 'users.email_verified_at', 'users.remember_token', 'users.created_at', 'users.updated_at')
            ->orderBy('users.name')
            ->get();
    }

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            'User ID',
            'Name',
            'Email',
            'Username',
            'Role',
            'Status',
            'Campaign',
            'Area',
            'Site',
            'Phone',
            'Total Assigned Tickets',
            'Total Solved Tickets',
            'Inbox Tickets',
            'Account Created',
        ];
    }

    /**
     * Map each row data
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->username,
            ucwords(str_replace('_', ' ', $user->role)),
            ucfirst($user->status),
            $user->campaign ?? 'N/A',
            $user->area ?? 'N/A',
            $user->site ?? 'N/A',
            $user->phone ?? 'N/A',
            $user->assigned_tickets ?? 0,
            $user->solved_tickets ?? 0,
            $user->inbox_tickets ?? 0,
            $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '',
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
            'A' => 10,  // User ID
            'B' => 25,  // Name
            'C' => 30,  // Email
            'D' => 20,  // Username
            'E' => 15,  // Role
            'F' => 12,  // Status
            'G' => 20,  // Campaign
            'H' => 15,  // Area
            'I' => 15,  // Site
            'J' => 18,  // Phone
            'K' => 22,  // Total Assigned
            'L' => 20,  // Total Solved
            'M' => 15,  // Inbox
            'N' => 20,  // Created
        ];
    }

    /**
     * Register events for advanced styling
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Insert header rows at top
                $sheet->insertNewRowBefore(1, 5);
                
                // XENA Logo/Branding - Row 1
                $sheet->setCellValue('A1', '📊 XENA - Ticket Distribution System');
                $sheet->mergeCells('A1:N1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                        'color' => ['rgb' => '1F4A5E'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);
                
                // Report Title - Row 2
                $sheet->setCellValue('A2', 'USER PERFORMANCE REPORT');
                $sheet->mergeCells('A2:N2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '2C5F7C'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                
                // Report Info - Row 3
                $sheet->setCellValue('A3', 'Generated: ' . now()->format('d F Y, H:i:s'));
                $sheet->setCellValue('H3', 'Generated By: ' . (auth()->user()->name ?? 'System'));
                $sheet->getStyle('A3:N3')->applyFromArray([
                    'font' => [
                        'size' => 10,
                        'italic' => true,
                        'color' => ['rgb' => '666666'],
                    ],
                ]);
                
                // Empty row for spacing - Row 4
                
                // Summary Info - Row 5
                $totalUsers = $this->collection()->count();
                $sheet->setCellValue('A5', 'Total Users: ' . $totalUsers);
                $sheet->mergeCells('A5:D5');
                $sheet->getStyle('A5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8F4F8'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                
                // Style the header row (now row 6)
                $sheet->getStyle('A6:N6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2C5F7C'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getRowDimension(6)->setRowHeight(25);
                
                // Apply borders to all data rows
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A6:N' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);
                
                // Alternating row colors for better readability
                for ($row = 7; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':N' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F9FAFB'],
                            ],
                        ]);
                    }
                }
                
                // Freeze header rows
                $sheet->freezePane('A7');
                
                // Auto-filter on header row
                $sheet->setAutoFilter('A6:N6');
            },
        ];
    }
}
