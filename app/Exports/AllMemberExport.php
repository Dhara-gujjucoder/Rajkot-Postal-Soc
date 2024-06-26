<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class AllMemberExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithStrictNullComparison
{
    protected $data,$columns;

    public function __construct($data,$columns)
    {
        $this->data = $data;
        $this->columns = $columns;
    }

    public function collection()
    {
        // $data = Member::orderBy('uid', 'ASC')->get();
        // // $this->total_row = count($data) + 1;
        // $data->push([]);
        // return $data;

        return $this->data;

    }

    public function headings(): array
    {
        // $heading = ['M.No', 'Registration no', 'Department name', 'Member name', 'Mobile', 'Email'];
        // return $heading;

        $headings = $this->columns;
        // if (isset($this->data[0])) {
        //     if (isset($this->data[0]->registration_no)) {
        //         $headings[] = 'Registration No';
        //     }
        //     if (isset($this->data[0]->name)) {
        //         $headings[] = 'Name';
        //     }
        //     if (isset($this->data[0]->profile_picture)) {
        //         $headings[] = 'Profile Picture'; // Assuming 'profile_picture' is a field in Member model
        //     }
        //     if (isset($this->data[0]->gender)) {
        //         $headings[] = 'Gender';
        //     }
        // }

        return $headings;
    }

    public function map($member): array
    {
        // if (is_object($member)) {
        //     return [
        //         $member->uid,
        //         $member->registration_no ?? '-',
        //         $member->department->department_name ?? '-',
        //         $member->name,
        //         $member->mobile_no,
        //         $member->email ?? '-'
        //     ];
        // }else {
        //     return [];
        // }

        $mappedData = [];

        foreach ($this->columns as $key => $value) {

            if($value == 'M.No'){
                $mappedData[] = $member->uid;
            }
            if($value == 'Registration No'){
                $mappedData[] = $member->registration_no;
            }
            if ($value == 'Name') {
                $mappedData[] =  $member->user->name ;
            }
            if($value == 'Profile Picture'){
                $mappedData[] = $member->profile_picture;
            }
            if($value == 'Gender'){
                $mappedData[] = $member->gender;
            }
            if($value == 'Email'){
                $mappedData[] = $member->user->email;
            }
            if($value == 'Birth Date'){
                $mappedData[] = $member->birthdate;
            }
            if($value == 'Mobile No'){
                $mappedData[] = $member->mobile_no;
            }
            if($value == 'Whatsapp No'){
                $mappedData[] = $member->whatsapp_no;
            }
            if($value == 'Current Address'){
                $mappedData[] = $member->current_address;
            }
            if($value == 'Parmenant Address'){
                $mappedData[] = $member->parmenant_address;
            }
            if($value == 'Signature'){
                $mappedData[] = $member->signature;
            }

            if($value == 'Department'){
                $mappedData[] = $member->department->department_name;
            }
            if($value == 'Company'){
                $mappedData[] = $member->company;
            }
            if($value == 'Designation'){
                $mappedData[] = $member->designation;
            }
            if($value == 'Salary'){
                $mappedData[] = $member->salary;
            }
            if($value == 'DA'){
                $mappedData[] = $member->da;
            }

            if($value == 'Aadhar Card No'){
                $mappedData[] = $member->aadhar_card_no;
            }
            if($value == 'Aadhar card'){
                $mappedData[] = $member->aadhar_card;
            }
            if($value == 'PAN No'){
                $mappedData[] = $member->pan_no;
            }
            if($value == 'PAN Card'){
                $mappedData[] = $member->pan_card;
            }
            if($value == 'Departmental ID Proof'){
                $mappedData[] = $member->department_id_proof;
            }

            if($value == 'Nominee Name'){
                $mappedData[] = $member->nominee_name;
            }
            if($value == 'Nominee Relation'){
                $mappedData[] = $member->nominee_relation;
            }
            if($value == 'Witness Signature'){
                $mappedData[] = $member->witness_signature;
            }

            if($value == 'Saving Account No'){
                $mappedData[] = $member->saving_account_no;
            }
            if($value == 'Bank Name'){
                $mappedData[] = $member->bank_name;
            }
            if($value == 'IFSC code'){
                $mappedData[] = $member->ifsc_code;
            }
            if($value == 'Branch Address'){
                $mappedData[] = $member->branch_address;
            }

        }

        return $mappedData;
    }

    public function styles(Worksheet $sheet)
    {
        $style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ];

        $substyle = [
            'alignment' => [
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                'wrapText' => true,
            ],
            'font' => [
                'bold'  =>  true,
            ],

        ];

        $sheet->getStyle('A1:F1')->applyFromArray($substyle);
        $sheet->getStyle('A1:F1')->applyFromArray($style);
    }

}
