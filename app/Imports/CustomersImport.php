<?php

namespace App\Imports;

use App\Customer;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomersImport implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    use RemembersRowNumber;

    /**
    * @inheritDoc
    */
    public function model(array $row)
    {
        return new Customer([
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'phone' => $row['phone'],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'bail|numeric|digits:12',
        ];
    }

    /**
     * @inheritDoc
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * @inheritDoc
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * @return int
     */
    public function recordsNumber(): int
    {
        return $this->getRowNumber() - 1;
    }
}
