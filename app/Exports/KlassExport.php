<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Mang;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyleInfo;

class KlassExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    protected $klassId;

    public function __construct($klassId)
    {
        $this->klassId = $klassId;
    }

    public function collection()
    {
        return User::where('role', "like", 'student')
            ->where('klass', $this->klassId)
            ->with('mangs')
            ->orderBy("perenimi", "ASC")
            ->get();
    }

    public function headings(): array
    {
        return [
            'Eesnimi',
            'Perekonnanimi',

            // All time
            'Mängud (kõik mängud)',
            'Punktid kokkku (kõik mängud)',
            'Keskmine täpsus (kõik mängud)',

            // Last month
            'Mängud (eelmine kuu)',
            'Punktid kokkku (eelmine kuu)',
            'Keskmine täpsus (eelmine kuu)',

            // Last week
            'Mängud (eelmine nädal)',
            'Punktid kokkku (eelmine nädal)',
            'Keskmine täpsus (eelmine nädal)',
        ];
    }

    public function map($user): array
    {
        // time windows
        $weekAgo = Carbon::now()->subWeek();
        $monthAgo = Carbon::now()->subMonth();

        // Helper closure to aggregate
        $aggregate = function ($query) {
            $games = $query->count();
            $points = $query->sum('experience');
            $accuracy = $games > 0 ? round($query->sum('accuracy_sum') / $games, 2) : 0;
            return [$games, $points, $accuracy];
        };

        // Filtered stats
        [$gamesWeek, $pointsWeek, $accWeek] = $aggregate($user->mangs->where('dt', '>=', $weekAgo));
        [$gamesMonth, $pointsMonth, $accMonth] = $aggregate($user->mangs->where('dt', '>=', $monthAgo));
        [$gamesAll, $pointsAll, $accAll] = $aggregate($user->mangs);

        return [
            $user->eesnimi,
            $user->perenimi,

            $gamesAll, $pointsAll, $accAll,
            $gamesMonth, $pointsMonth, $accMonth,
            $gamesWeek, $pointsWeek, $accWeek,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                $range = "A1:{$lastColumn}{$lastRow}";

                // Convert range to Excel Table
                $table = new Table($range, 'KlassStats');
                $table->setShowHeaderRow(true);

                $sheet->addTable($table);
            },
        ];
    }
}