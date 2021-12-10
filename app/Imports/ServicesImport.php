<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ServicesImport implements ToCollection, WithStartRow
{
    public function collection(Collection $row)
	{
	    return $row;
	}

	public function startRow(): int
    {
        return 2;
    }

}
