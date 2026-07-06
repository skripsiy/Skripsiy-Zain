<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UserReportsExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithEvents, WithTitle
{
    private ?\Illuminate\Support\Collection $rows = null;
    private int $index = 0;

    private function rows()
    {
        if ($this->rows === null) {
            $today = \Carbon\Carbon::today()->toDateString();
            $this->rows = User::select('users.*')
                ->selectRaw('COUNT(DISTINCT CASE WHEN tickets.assigned_to_user_id = users.id AND DATE(tickets.datereport) = ? THEN tickets.idTicket END) as assigned_tickets', [$today])
                ->selectRaw('COUNT(DISTINCT CASE WHEN tickets.solved_by_user_id = users.id AND DATE(tickets.datesolved) = ? THEN tickets.idTicket END) as solved_tickets', [$today])
                ->selectRaw('COUNT(DISTINCT CASE WHEN tickets.assigned_to_user_id = users.id AND tickets.status = "QUEUED" THEN tickets.idTicket END) as inbox_tickets')
                ->leftJoin('tickets', function($join) {
                    $join->on('tickets.assigned_to_user_id', '=', 'users.id')
                         ->orOn('tickets.solved_by_user_id', '=', 'users.id');
                })
                ->groupBy('users.id', 'users.name', 'users.email', 'users.password', 'users.role', 'users.status', 'users.campaign', 'users.area', 'users.site', 'users.username', 'users.phone', 'users.email_verified_at', 'users.remember_token', 'users.created_at', 'users.updated_at')
                ->orderBy('users.name')
                ->get();
        }
        return $this->rows;
    }

    /**
     * Get users collection with ticket statistics
     */
    public function collection()
    {
        // Reset row index counter
        $this->index = 0;
        return $this->rows();
    }

    /**
     * Define sheet title
     */
    public function title(): string
    {
        return 'Laporan User';
    }

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Email',
            'Username',
            'Role',
            'Status',
            'Campaign',
            'Area',
            'Site',
            'Telepon',
            'Tiket Di-assign',
            'Tiket Solved',
            'Tiket Inbox',
            'Akun Dibuat',
        ];
    }

    /**
     * Map each row data
     */
    public function map($user): array
    {
        $this->index++;
        return [
            $this->index,
            $user->name,
            $user->email,
            $user->username,
            ucwords(str_replace('_', ' ', $user->role)),
            ucfirst($user->status),
            $user->campaign ?? '-',
            $user->area ?? '-',
            $user->site ?? '-',
            $user->phone ?? '-',
            (int)($user->assigned_tickets ?? 0),
            (int)($user->solved_tickets ?? 0),
            (int)($user->inbox_tickets ?? 0),
            $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 25,  // Nama
            'C' => 30,  // Email
            'D' => 20,  // Username
            'E' => 15,  // Role
            'F' => 12,  // Status
            'G' => 20,  // Campaign
            'H' => 15,  // Area
            'I' => 15,  // Site
            'J' => 18,  // Telepon
            'K' => 18,  // Tiket Di-assign
            'L' => 18,  // Tiket Solved
            'M' => 15,  // Tiket Inbox
            'N' => 20,  // Akun Dibuat
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
                
                // Color Palette
                $colorPrimary   = '1F4A5E';
                $colorSecondary = '2C5F7C';
                $colorAccent    = 'E8F4F8';
                $colorZebra     = 'F4F8FB';
                $colorTotal     = 'DCE9F0';
                $colorBorder    = 'D5DEE4';

                // Insert header rows at top
                $sheet->insertNewRowBefore(1, 5);
                
                // Row 1: Banner "XENA • Ticket Distribution System"
                $sheet->setCellValue('A1', 'XENA • Ticket Distribution System');
                $sheet->mergeCells('A1:N1');
                $sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $colorPrimary],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(34);
                
                // Row 2: Sub-judul "LAPORAN PERFORMA PENGGUNA"
                $sheet->setCellValue('A2', 'LAPORAN PERFORMA PENGGUNA');
                $sheet->mergeCells('A2:N2');
                $sheet->getStyle('A2:N2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => $colorSecondary],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $colorAccent],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(24);
                
                // Row 3: Meta Info (kiri: Dibuat, kanan: Oleh)
                $sheet->setCellValue('A3', 'Dibuat: ' . now()->format('d F Y, H:i'));
                $sheet->setCellValue('N3', 'Oleh: ' . (auth()->user()->name ?? 'System'));
                $sheet->getStyle('A3:N3')->applyFromArray([
                    'font' => [
                        'size' => 9,
                        'italic' => true,
                        'color' => ['rgb' => '666666'],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('N3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getRowDimension(3)->setRowHeight(18);
                
                // Row 4 & 5: Summary Cards
                $totalUsers    = count($this->rows());
                $sumAssigned   = $this->rows()->sum('assigned_tickets');
                $sumSolved     = $this->rows()->sum('solved_tickets');
                $sumInbox      = $this->rows()->sum('inbox_tickets');

                // Summary Row Labels (Row 4)
                $sheet->setCellValue('A4', 'TOTAL USER');
                $sheet->setCellValue('D4', 'TIKET DI-ASSIGN');
                $sheet->setCellValue('G4', 'TIKET SOLVED');
                $sheet->setCellValue('K4', 'TIKET INBOX');

                $sheet->getStyle('A4:N4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 9,
                        'color' => ['rgb' => $colorSecondary],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $colorAccent],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => $colorBorder],
                        ],
                    ],
                ]);
                $sheet->getRowDimension(4)->setRowHeight(20);

                // Summary Row Values (Row 5)
                $sheet->setCellValue('A5', $totalUsers);
                $sheet->setCellValue('D5', $sumAssigned);
                $sheet->setCellValue('G5', $sumSolved);
                $sheet->setCellValue('K5', $sumInbox);

                $sheet->getStyle('A5:N5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => $colorPrimary],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $colorAccent],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => $colorBorder],
                        ],
                    ],
                ]);
                $sheet->getRowDimension(5)->setRowHeight(28);

                // Merge cells for the summary cards
                $sheet->mergeCells('A4:C4');
                $sheet->mergeCells('D4:F4');
                $sheet->mergeCells('G4:J4');
                $sheet->mergeCells('K4:N4');

                $sheet->mergeCells('A5:C5');
                $sheet->mergeCells('D5:F5');
                $sheet->mergeCells('G5:J5');
                $sheet->mergeCells('K5:N5');

                // Row 6: Table Header Styling
                $sheet->getStyle('A6:N6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $colorSecondary],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => $colorBorder],
                        ],
                    ],
                ]);
                $sheet->getRowDimension(6)->setRowHeight(26);

                // If table has user data, apply data styles & total row
                if ($totalUsers > 0) {
                    $lastDataRow = 6 + $totalUsers;

                    // Data Rows border and vertical alignment
                    $sheet->getStyle('A7:N' . $lastDataRow)->applyFromArray([
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => $colorBorder],
                            ],
                        ],
                    ]);

                    // Alignment overrides
                    $sheet->getStyle('A7:A' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('E7:F' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('K7:N' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Row height and zebra striping
                    for ($row = 7; $row <= $lastDataRow; $row++) {
                        $sheet->getRowDimension($row)->setRowHeight(20);
                        if ($row % 2 === 0) {
                            $sheet->getStyle('A' . $row . ':N' . $row)->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => $colorZebra],
                                ],
                            ]);
                        }
                    }

                    // Total Row
                    $totalRow = $lastDataRow + 1;
                    $sheet->setCellValue('A' . $totalRow, 'TOTAL');
                    $sheet->setCellValue('K' . $totalRow, "=SUM(K7:K{$lastDataRow})");
                    $sheet->setCellValue('L' . $totalRow, "=SUM(L7:L{$lastDataRow})");
                    $sheet->setCellValue('M' . $totalRow, "=SUM(M7:M{$lastDataRow})");

                    $sheet->mergeCells('A' . $totalRow . ':J' . $totalRow);

                    $sheet->getStyle('A' . $totalRow . ':N' . $totalRow)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 10,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $colorTotal],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => $colorBorder],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle('K' . $totalRow . ':N' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getRowDimension($totalRow)->setRowHeight(22);
                }

                // Gridlines and layout configuration
                $sheet->setShowGridlines(false);
                $sheet->freezePane('A7');
                $sheet->setAutoFilter('A6:N6');
            },
        ];
    }
}
