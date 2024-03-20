<?php

namespace App\Http\Controllers;

use App\Models\{sold_land};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SoldLandController extends Controller
{
    public function view(Request $request)
    {
        $area_range_start = $request->input('area_range_start');
        $area_range_end = $request->input('area_range_end');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        // Get all sold lands by default
        $query = DB::table('sold_lands');

        // Filter by survey number if provided
        if ($request->has('survey_number')) {
            $query->where('survey_number', 'like', '%' . $request->input('survey_number') . '%');
        }

        // Filter by district if provided
        $query->when($request->filled('district'), function ($query) use ($request) {
            $query->where('district', $request->input('district'));
        });

        // // Filter by village if provided
        $query->when($request->filled('village'), function ($query) use ($request) {
            $query->where('village', $request->input('village'));
        });

   

           // dd($start_date);
           if ($start_date && $end_date) {
            // Convert dates to Carbon instances to ensure correct format and handle any timezone issues
            $start = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay(); // Ensures the comparison includes the start of the start_date
            $end = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay(); // Ensures the comparison includes the end of the end_date
            $query->whereBetween('register_date', [$start, $end]);
            // dd($query);
        } elseif ($start_date) {
            // If only start_date is provided
            $start = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
            $query->where('register_date', '>=', $start);
        } elseif ($end_date) {
            // If only end_date is provided
            $end = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();
            $query->where('register_date', '<=', $end);
        }


        if ($area_range_start !== null || $area_range_end !== null) {
            $query->where(function ($q) use ($area_range_start, $area_range_end) {
                if ($area_range_start !== null) {
                    $q->where('total_area', '>=', $area_range_start);
                }
                if ($area_range_end !== null) {
                    $q->where('total_area', '<=', $area_range_end);
                }
            });
        }
    
        if ($area_range_start !== null || $area_range_end !== null) {
            $query->where(function ($q) use ($area_range_start, $area_range_end, $request) {
                if ($area_range_start !== null) {
                    $q->where('total_area', '>=', $area_range_start);
                }
                if ($area_range_end !== null) {
                    $q->where('total_area', '<=', $area_range_end);
                }
                // Filter by area unit if provided
                if ($request->filled('area_unit')) {
                    $area_unit = $request->input('area_unit');
                    if ($area_unit === 'Acres') {
                        // Convert square feet to acres and apply the filter
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('total_area_unit', 'Square Feet')
                                ->where('total_area', '>=', $area_range_start / 43560)
                                ->where('total_area', '<=', $area_range_end / 43560);
                        });
                    } elseif ($area_unit === 'Square Feet') {
                        // Convert acres to square feet and apply the filter
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('total_area_unit', 'Acres')
                                ->where('total_area', '>=', $area_range_start * 43560)
                                ->where('total_area', '<=', $area_range_end * 43560);
                        });
                    }
                }
                
            });
        }
        
        // $query->orWhere(function ($query) use ($request) {
        //     $query->when($request->has('start_date') || $request->has('end_date'), function ($query) use ($request) {
        //         $query->whereBetween('register_date', [$request->input('start_date'), $request->input('end_date')]);
        //     })
        //         ->when($request->has('area_range_start') || $request->has('area_range_end'), function ($query) use ($request) {
        //             $query->orWhereBetween('total_area', [$request->input('area_range_start'), $request->input('area_range_end')]);
        //         });
        // });

        // Get the filtered sold lands
        $data = $query->orderBy('created_at', 'desc')->get();

        // $data = DB::table('sold_lands')->orderBy('created_at', 'desc')->get();
        $uniqueVillages = DB::table('sold_lands')->whereNotNull('village')->where('village', '<>', '')->distinct()->pluck('village')->toArray();
        $uniqueDistricts = DB::table('sold_lands')->whereNotNull('district')->where('district', '<>', '')->distinct()->pluck('district')->toArray();


        // Log the SQL query being executed
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        Log::info("SQL query", ['query' => $sql, 'bindings' => $bindings]);
        return view('pages.sold-lands.index', [
            'data' => $data,
            'uniqueVillages' => $uniqueVillages,
            'uniqueDistricts' => $uniqueDistricts,
            'start_date' => $start_date,
        ]);
    }
    public function add()
    {
        $data = DB::table('sold_lands')->orderBy('created_at', 'desc')->get();

        return view('pages.sold-lands.add', [
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            // 'district_number' => 'string|max:255', 
            // Example validation for string and max length
            // Add custom validation rules for other fields as needed
        ]);

        // Add the 'created_by' field with the current user's ID
        $requestData = $request->all();
        $requestData['created_by'] = Auth::id();

        // If the validation passes, store the sold land details
        Sold_land::create($requestData);

        // Redirect or return a response
        return redirect()->route('soldLand.view')->with('success', 'Sold Land details added successfully.');
    }


    // Show the form for editing the specified sold land detail
    public function edit($id)
    {
        $soldLand = Sold_land::findOrFail($id);
        return view('pages.sold-lands.add', ['soldLand' => $soldLand]);
    }

    // Update the specified sold land detail in the database
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'district_number' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village_number' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'survey_number' => 'nullable|string|max:255',
            'wet_land' => 'nullable|string|max:255',
            'dry_land' => 'nullable|string|max:255',
            'plot' => 'nullable|string|max:255',
            'traditional_land' => 'nullable|string|max:255',
            'total_area' => 'nullable|string|max:255',
            'total_area_unit' => 'nullable|string|max:255',
            'total_wet_land' => 'nullable|string|max:255',
            'total_dry_land' => 'nullable|string|max:255',
            'gap' => 'nullable|string|max:255',
            'sale_amount' => 'nullable|string|max:255',
            'total_sale_amount' => 'nullable|string|max:255',
            'registration_office' => 'nullable|string|max:255',
            'register_number' => 'nullable|string|max:255',
            'register_date' => 'nullable|date',
            'book_number' => 'nullable|string|max:255',
            'name_of_the_purchaser' => 'nullable|string|max:255',
            'balance_land' => 'nullable|string|max:255',
            'remark' => 'nullable|string|max:255',
        ]);

        // Find the sold land detail by ID
        $soldLand = Sold_land::findOrFail($id);

        // Add the 'updated_by' field with the current user's ID
        $validatedData['updated_by'] = Auth::id();

        // Update the sold land detail with the validated data
        $soldLand->update($validatedData);

        // Redirect or return a response
        return view('pages.sold-lands.show', ['soldLands' => $soldLand, 'id' => $id]);

        // return redirect()->route('soldLand.index')->with('success', 'Sold Land details updated successfully.');
    }

    public function show($id)
    {
        // Retrieve the sold land detail by ID
        $soldLands = Sold_land::findOrFail($id);
        // dd($soldLands);
        // Return a view withdd the sold land details
        return view('pages.sold-lands.show', ['soldLands' => $soldLands, 'id' => $id]);
    }




    public function bulkUploadSoldLandData(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:csv,txt|max:10240', // Adjust max file size as needed
        ]);

        $filePath = $request->file('document')->getRealPath();
        $file = fopen($filePath, 'r');

        // Skip the first two rows
        fgetcsv($file); // Skip first row
        fgetcsv($file); // Skip second row

        DB::beginTransaction();

        try {
            while (($line = fgetcsv($file)) !== false) {
                if (!empty($line[0])) {
                if (array_filter($line)) {

                    $dateFormats = ['d-m-Y', 'd/m/Y'];
                    $formattedDate = null;
                    foreach ($dateFormats as $format) {
                        try {
                            $formattedDate = Carbon::createFromFormat($format, trim($line[19]))->toDateString();
                            break; // Format matched, break out of the loop
                        } catch (\Exception $e) {
                            // Catch the exception and continue trying other formats
                        }
                    }

                    $data['register_date'] = $formattedDate ?? null;

                    // Extract data from each row
                    $data = [
                        // 'index_id' => $line[0] ?? null,
                        'index_id' => $line[1] ?? null,
                        'state' => $line[2] ?? null,
                        'district_number' => $line[3] ?? null,
                        'district' => $line[4] ?? null,
                        'village_number' => $line[5] ?? null,
                        'village' => $line[6] ?? null,
                        'survey_number' => $line[7] ?? null,
                        'wet_land' => $line[8] ?? null,
                        'dry_land' => $line[9] ?? null,
                        'plot' => $line[10] ?? null,
                        'traditional_land' => $line[11] ?? null,
                        'total_area' => $line[12] ?? null,
                        'total_area_unit' => $line[13] ?? null,
                        'total_wet_land' => $line[14] ?? null,
                        'total_dry_land' => $line[15] ?? null,
                        'gap' => $line[16] ?? null,
                        'sale_amount' => $line[17] ?? null,
                        'total_sale_amount' => $line[18] ?? null,
                        'registration_office' => $line[19] ?? null,
                        'register_number' => $line[20] ?? null,
                        'register_date' => $formattedDate ?? null,
                        'book_number' => $line[22] ?? null,
                        'name_of_the_purchaser' => $line[23] ?? null,
                        'balance_land' => $line[24] ?? null,
                        'remark' => $line[25] ?? null,
                        'created_by' => Auth::user()->id,


                        // Add other fields here...
                    ];

                    // Validate the data
                    $validator = Validator::make($data, [
                        'index_id' => 'nullable|string|max:255',
                        'district_number' => 'nullable|string|max:255',
                        'district' => 'nullable|string|max:255',
                        'village_number' => 'nullable|string|max:255',
                        'village' => 'nullable|string|max:255',
                        'survey_number' => 'nullable|string|max:255',
                        'wet_land' => 'nullable|string|max:255',
                        'dry_land' => 'nullable|string|max:255',
                        'plot' => 'nullable|string|max:255',
                        'traditional_land' => 'nullable|string|max:255',
                        'total_area' => 'nullable|string|max:255',
                        'total_area_unit' => 'nullable|string|max:255',
                        'total_wet_land' => 'nullable|string|max:255',
                        'total_dry_land' => 'nullable|string|max:255',
                        'gap' => 'nullable|string|max:255',
                        'sale_amount' => 'nullable|string|max:255',
                        'total_sale_amount' => 'nullable|string|max:255',
                        'registration_office' => 'nullable|string|max:255',
                        'register_number' => 'nullable|string|max:255',
                        // 'register_date' => 'nullable|date',
                        'book_number' => 'nullable|string|max:255',
                        'name_of_the_purchaser' => 'nullable|string|max:255',
                        'balance_land' => 'nullable|string|max:255',
                        'remark' => 'nullable|string|max:255',
                        // Add validation rules for other fields...
                    ]);

                    if ($validator->fails()) {
                        throw new \Exception('Validation failed for one or more rows.');
                    }

                    // Create or update the SoldLand record, if the index_id  is unique it will create else update
                    Sold_land::updateOrCreate(['index_id' => $data['index_id']], $data);
                }
            }
        }
            DB::commit();

            // Redirect or return a response
            return redirect()->back()->with('success', 'Bulk upload completed successfully.');
        } catch (\Exception $e) {
            Log::error('Bulk upload failed: ' . $e->getMessage());
            DB::rollBack();

            // Close the file
            fclose($file);

            // Redirect back with error message
            return redirect()->back()->with('error', 'Bulk upload failed. ' . $e->getMessage());
        }
    }
}
