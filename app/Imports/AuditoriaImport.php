<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AuditoriaImport implements ToCollection
{
    protected int $limiteMinutos;
    public array $grupos = [];

    public function __construct(int $limiteMinutos = 20)
    {
        $this->limiteMinutos = $limiteMinutos;
    }

    public function collection(Collection $rows)
    {
        $grouped = [];
      
        foreach ($rows as $index => $row) {
            if ($index === 0)
                continue;

            $origem = $row[15];
            $passageiro = $row[20];


            $dataInicio = Carbon::createFromFormat('d/m/Y H:i:s', $row[12]);


            $key = $origem . '|' . $passageiro;

            $grouped[$key][] = [
                'row' => $row,
                'data' => $dataInicio,
            ];
        }

        $finalGroups = [];

        foreach ($grouped as $key => $entries) {
            $count = count($entries);
            if ($count < 2)
                continue;

            $used = [];

            for ($i = 0; $i < $count; $i++) {
                if (in_array($i, $used))
                    continue;

                $currentGroup = [$entries[$i]['row']];
                $used[] = $i;

                for ($j = $i + 1; $j < $count; $j++) {
                    if (in_array($j, $used))
                        continue;

                    $diff = $entries[$i]['data']->diffInMinutes($entries[$j]['data']);
                    if ($diff <= $this->limiteMinutos) {
                        $currentGroup[] = $entries[$j]['row'];
                        $used[] = $j;
                    }
                }

                
                if (count($currentGroup) > 1) {
                    $finalGroups[] = $currentGroup;
                }
            }
        }

        $this->grupos = $finalGroups;

    }
}