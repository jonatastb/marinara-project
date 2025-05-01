<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GruposExport implements FromArray, WithHeadings
{
    protected $dados;

    public function __construct(array $dados)
    {
        $this->dados = $dados;
    }

    public function array(): array
    {
        $planilha = [];
        foreach ($this->dados as $grupo) {
            $planilha[] = $grupo;
            $planilha[] = array_fill(0, count($grupo), null);
        }
        return $planilha;
    }

    public function headings(): array
    {
        return [];
    }
}
