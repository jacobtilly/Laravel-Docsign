<?php

namespace JacobTilly\LaravelDocsign\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    public function documentComplete(Request $request)
    {

        $data = $request->validate([
            'document_id' => 'required',
        ]);

        $jobClass = config('docsign.callbacks.document_complete_job');

        if ($jobClass) {
            $jobClass::dispatch($data);
        }

        return response()->json(['message' => 'Job dispatched'], 200);
    }

    public function partySign(Request $request)
    {

        $data = $request->validate([
            'document_id' => 'required',
            'party_id' => 'required',
        ]);

        $jobClass = config('docsign.callbacks.party_sign_job');
        if ($jobClass) {
            $jobClass::dispatch($data);
        }

        return response()->json(['message' => 'Job dispatched'], 200);
    }
}
