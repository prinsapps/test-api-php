<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\ServicesImport;
use App\Exports\ServicesExport;

class ServiceController extends Controller
{

    /**
     * Store a newly created row in csv.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'Ref' => 'required',
                'Centre' => 'required',
                'Service' => 'required',
                'Country' => 'required'
            ]);

            if ($validator->fails()) {
                return response(['error' => $validator->errors(), 'Validation Error']);
            }

            $exportData = [];
            if(file_exists(public_path('csv/services.csv'))) {
                $result = Excel::toArray(new ServicesImport, public_path('csv/services.csv'));
                foreach ($result[0] as $row) {
                    $exportData[] = array('Ref' => $row[0], 'Centre' => $row[1], 'Service' => $row[2], 'Country' => $row[3]);
                }
            }

            $exportData[] = array('Ref' => $data['Ref'], 'Centre' => $data['Centre'], 'Service' => $data['Service'], 'Country' => $data['Country']);
            if(!file_exists(public_path('csv/services.csv'))) {
                touch(public_path('csv/services.csv'), strtotime('-1 days'));
            }
            $success = Excel::store(new ServicesExport($exportData), public_path('csv/services.csv'));
            if($success) {
               return response(['message' => 'Created successfully', 'status' => 'success'], 200); 
            }

            return response(['message' => 'Something went wrong', 'status' => 'error'], 200);
        } catch (\Throwable $e) {
            return response(['error' => $e->getMessage(), 'status' => 'error'], 500);
        }
    }

    /**
     * Display the specified csv row.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($countryCode)
    {
        try {
            if(file_exists(public_path('csv/services.csv'))) {
                $result = Excel::toArray(new ServicesImport, public_path('csv/services.csv'));
                $exportData = [];
                foreach ($result[0] as $row) {
                    if(strtolower($row[3]) == strtolower($countryCode)) {
                        $exportData[] = array('Ref' => $row[0], 'Centre' => $row[1], 'Service' => $row[2], 'Country' => $row[3]);    
                    }
                }
                return response(['services' => $exportData, 'status' => 'Success'], 200);
            }
            return response(['message' => 'Not found', 'status' => 'error'], 200);
        } catch (\Throwable $e) {
            return response(['error' => $e->getMessage(), 'status' => 'error'], 500);
        }
    }

    /**
     * Update the specified row in csv.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ref)
    {
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'Centre' => 'required',
                'Service' => 'required',
                'Country' => 'required'
            ]);

            if ($validator->fails()) {
                return response(['error' => $validator->errors(), 'Validation Error']);
            }

            $isFound = false;
            $exportData = [];
            if(file_exists(public_path('csv/services.csv'))) {
                $result = Excel::toArray(new ServicesImport, public_path('csv/services.csv'));
                foreach ($result[0] as $row) {
                    if($row[0] == $ref) {
                        $isFound = true;
                        $exportData[] = array('Ref' => $row[0], 'Centre' => $data['Centre'], 'Service' => $data['Service'], 'Country' => $data['Country']);
                    } else {
                        $exportData[] = array('Ref' => $row[0], 'Centre' => $row[1], 'Service' => $row[2], 'Country' => $row[3]);
                    }
                }
            }
            
            if($isFound == true) {
                if(!file_exists(public_path('csv/services.csv'))) {
                    touch(public_path('csv/services.csv'), strtotime('-1 days'));
                }
                $success = Excel::store(new ServicesExport($exportData), public_path('csv/services.csv'));
                if($success) {
                   return response(['message' => 'Updated successfully', 'status' => 'success'], 200); 
                }    
            }

            return response(['message' => 'Not found', 'status' => 'error'], 200);
        } catch (\Throwable $e) {
            return response(['error' => $e->getMessage(), 'status' => 'error'], 500);
        }
    }
}