<?php

namespace App\Imports;

use App\Models\Member;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToCollection;

class MemberImport implements ToModel
{
    public function model(array $row)
    {
        // Define how to create a model from the Excel row data
        if(isset($row[1]) && $row[1]){

            return new Member([
                'id' => $row[1],
                'company' => $row[2],
                // Add more columns as needed
            ]);
        }
    }
}
