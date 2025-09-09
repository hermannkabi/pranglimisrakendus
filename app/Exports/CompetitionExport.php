<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Mang;
use App\Models\User;
use App\Models\Competition;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Http\Controllers\CompetitionController;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyleInfo;

class CompetitionExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    protected $competitionId;

    public function __construct($competitionId)
    {
        $this->competitionId = $competitionId;
        $this->leaderboard = app(CompetitionController::class)->getLeaderboard($competitionId, null);
    }

    public function collection()
    {
        return collect(array_map(function ($e){return $e["user"];}, $this->leaderboard->toArray()));
    }

    public function headings(): array
    {
        return [
            'Koht',
            'Eesnimi',
            'Perekonnanimi',
            'Tulemus'
        ];
    }

    public function map($user): array
    {
        // Need to get the user's rank and result
        $userEntry = $this->leaderboard->firstWhere('user.id', $user->id);
        $userRank = $userEntry ? $userEntry['rank'] : "-";
        $totalScore = $userEntry ? $userEntry['total_score'] : "-";

        return [
            $userRank,
            $user->eesnimi,
            $user->perenimi,
            $totalScore,
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
                $table = new Table($range, 'CompetitionStats');
                $table->setShowHeaderRow(true);

                $sheet->addTable($table);
            },
        ];
    }
}