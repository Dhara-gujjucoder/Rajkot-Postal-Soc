<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Imports\MemberImport;
use App\Imports\SalaryDedImport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class MemberImportController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('import.member', [
            'page_title' => __('Import Member')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Excel::import(new MemberImport, $request->file('memberexcel'));
        // Excel::import(new SalaryDedImport, $request->file('memberexcel'));

     
        return redirect()->route('members.index')
            ->withSuccess(__('New member is added successfully.'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storewithimage(Request $request): RedirectResponse
    {
        // Excel::import(new MemberImport, $request->file('memberexcel'));
        $objphpexcel = IOFactory::load($request->file('memberexcel'));
        
        // // ->getSheetByName("Sheet1")
        foreach ($objphpexcel->getActiveSheet()->getDrawingCollection() as $drawing) {
      
            $cellID = $drawing->getCoordinates();
           
            $i = preg_replace('/[^0-9]/', '', $cellID);
            $j = preg_replace('/[^a-zA-Z]/', '', $cellID);

    
            $images[$i]['name'] = $objphpexcel->getActiveSheet()->getCell('D' . $i)->getValue();

            if ($drawing instanceof Drawing) {
                $zipReader = fopen($drawing->getPath(), 'r');
                $imageContents = '';
                while (!feof($zipReader)) {
                    $imageContents .= fread($zipReader, 1024);
                }
                fclose($zipReader);
                $extension = $drawing->getExtension();
                if ($j == 'H') {

                    $myFileName = 'images\profile_picture\\' . rand() . '_' . $drawing->getCoordinates() . '.' . $extension;
                    file_put_contents($myFileName, $imageContents);
                    $images[$i]['profile_picture'] =  $myFileName;

                } elseif ($j == 'I') {

                    $myFileName = 'images\signature\\' . rand() . '_' . $drawing->getCoordinates() . '.' . $extension;
                    file_put_contents($myFileName, $imageContents);
                    $images[$i]['signature'] =  $myFileName;
                }

                $images[$i]['name'] =  $objphpexcel->getActiveSheet()->getCell('D' . $i)->getValue();
                $images[$i]['no'] =  $objphpexcel->getActiveSheet()->getCell('B' . $i)->getValue();
                $images[$i]['registration_no'] =  $objphpexcel->getActiveSheet()->getCell('C' . $i)->getValue();
                $images[$i]['mobile_no'] =  $objphpexcel->getActiveSheet()->getCell('E' . $i)->getValue();
                $images[$i]['whatsapp_no'] =  $objphpexcel->getActiveSheet()->getCell('F' . $i)->getValue();
                $images[$i]['aadhar_card_no'] =  $objphpexcel->getActiveSheet()->getCell('G' . $i)->getValue();
            }
        }
        foreach ($images as $key => $value) {
            $user = new User();
            // $user->id = $row[1];
            $user->name = $value['name'];
            $user->email = $value['name'];
            $user->password = Hash::make('rajkotpostalsoc12#');
            $user->save();
            $user->assignRole('User');

            $member = new Member();
            $member->user_id = $user->id;
            $member->registration_no = $value['registration_no'];
            $member->uid =  $value['no'];
            $member->mobile_no =  $value['mobile_no'];
            $member->whatsapp_no = $value['whatsapp_no'];
            $member->aadhar_card_no =  $value['aadhar_card_no'];
            $member->profile_picture = isset($value['profile_picture']) ? $value['profile_picture'] : '';
            $member->signature =  isset($value['signature']) ? $value['signature'] : '';
            $member->status = 0;
            $member->save();
        }
        return redirect()->route('members.index')
            ->withSuccess(__('New member is added successfully.'));
    }
}
