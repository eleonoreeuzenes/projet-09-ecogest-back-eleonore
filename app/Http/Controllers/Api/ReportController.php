<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function submitReport(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $validatedData = $request->validate([
            'ID' => 'required',
            'title' => 'required',
            'authorID' => 'required',
            'result' => 'required',
            'content' => 'nullable',
        ]);
     
        Mail::to('report@ecogest.dev')->send(new \App\Mail\ReportMail($validatedData));

        return response()->json(['message' => 'Formulaire soumis avec succès']);
    }
}
