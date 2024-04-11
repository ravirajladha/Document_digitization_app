<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\{Receiver, Receiver_type, Master_doc_type};

class ReceiverController extends Controller
{
    //receiver types function
    public function receiverType()
    {
        $data = Receiver_type::get();
        return view('pages.data-sets.receiver-type', ['data' => $data]);
    }

    public function addReceiverType(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:receiver_types', // Adjust the table name as needed
        ]);

        // Create a new receiver type
        $receiverType = new Receiver_type;
        $receiverType->name = $request->name;
        // Assign other fields as necessary
        $receiverType->created_by =  Auth::user()->id;
        // Save the receiver type to the database
        $receiverType->save();

        return response()->json(['success' => 'Receiver type added successfully.']);
    }

    public function updateReceiverType(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:receiver_types,id',
            'name' => 'required|string|max:255', // Validation rules as per your requirements
        ]);

        try {
            $receiverType = Receiver_type::findOrFail($request->id);
            $receiverType->name = $request->name;
            // Update other fields as necessary

            $receiverType->save();

            return response()->json(['success' => 'Receiver type updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the receiver type.'], 500);
        }
    }
    public function getUpdatedReceiverTypes()
    {
        $receiverTypes = Receiver_type::get(); // Assuming ReceiverType is your model name
        return response()->json($receiverTypes);
    }
    //receivers
    public function showReceivers()
    {
        $data = Receiver::with('receiverType')
            ->withCount('documentAssignments') // Add the count of document assignments
            ->orderBy('created_at', 'desc')
            ->get();

        $receiverTypes = Receiver_type::all();
        $documentTypes = Master_doc_type::orderBy('name')->get();

        return view('pages.receivers.receivers', [
            'data' => $data,
            'receiverTypes' => $receiverTypes,
            'documentTypes' => $documentTypes

        ]);
    }


    public function getUpdatedReceivers()
    {
        // Fetch receivers with the receiver type name
        $receivers = Receiver::with('receiverType')
            ->withCount('documentAssignments') // Add the count of document assignments
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform the data to include the receiver type name
        $receivers = $receivers->map(function ($receiver) {
            return [
                'id' => $receiver->id,
                'name' => $receiver->name,
                'phone' => $receiver->phone,
                'city' => $receiver->city,
                'email' => $receiver->email,
                'status' => $receiver->status,
                'receiver_type_name' => optional($receiver->receiverType)->name, // Get the name from the relationship
                'document_assignments_count' => $receiver->document_assignments_count, // Get the name from the relationship
            ];
        });

        return response()->json($receivers);
    }


    public function addReceiver(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|size:10|regex:/^\d{10}$/',
            'email' => 'required|string|email|max:255|unique:receivers,email',
            'city' => 'required|string|max:255',
            'receiver_type_id' => 'required|exists:receiver_types,id',
        ]);

        // Create a new receiver
        $receiver = new Receiver;
        $receiver->name = $request->name;
        $receiver->phone = $request->phone;
        $receiver->email = $request->email;
        $receiver->city = $request->city;
        $receiver->receiver_type_id = $request->receiver_type_id;
        $receiver->created_by = Auth::user()->id; // or Auth::user()->id;
        $receiver->save();

        // Return a JSON response indicating success
        return response()->json(['success' => 'Receiver added successfully.']);
    }

    public function updateReceiver(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:receivers,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:receivers,email,' . $request->id, // Ensure email is unique except for the current receiver
            'city' => 'required|string|max:255',
            'receiver_type_id' => 'required|exists:receiver_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $receiver = Receiver::findOrFail($request->id);
            $receiver->name = $request->name;
            $receiver->phone = $request->phone;
            $receiver->email = $request->email;
            $receiver->city = $request->city;
            $receiver->status = $request->status;
            $receiver->receiver_type_id = $request->receiver_type_id;
            // Add any additional fields you want to update here

            $receiver->save();

            return response()->json(['success' => 'Receiver updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the receiver.'], 500);
        }
    }


}
