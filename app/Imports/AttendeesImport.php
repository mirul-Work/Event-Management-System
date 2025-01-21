<?php
namespace App\Imports;

use App\Models\Attendee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendeesImport implements ToModel, WithHeadingRow
{
    /**
     * Map each row of the file to the Attendee model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Attendee([
            'name' => $row['name'],
            'email' => $row['email'],
            'phone_number' => $row['phone_number'],
            'seat_category' => $row['seat_category'], // Regular, VIP, VVIP
        ]);
    }
}
