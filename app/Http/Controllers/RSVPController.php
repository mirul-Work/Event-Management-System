<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RSVPController extends Controller
{
    /**
     * Display the RSVP page for the given seat type and token.
     *
     * @param string $seat_category
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function show($seat_category, $token)
    {
        // Retrieve the attendee by token
        $attendee = Attendee::where('token', $token)->firstOrFail();

        // Check if the seat type matches
        if ($attendee->seat_category !== $seat_category) {
            abort(404); // Token doesn't match seat type
        }

        // Check if attendee has already responded
        if (in_array($attendee->status, ['accepted', 'rejected'])) {
            return redirect()->route('already.responded', [
                'seat_category' => $seat_category,
                'token' => $token
            ]);
        }

        // Return the appropriate RSVP view
        return view('rsvp.' . strtolower($seat_category), compact('attendee'));
    }

    /**
     * Handle the RSVP acceptance or rejection.
     *
     * @param string $seat_category
     * @param string $token
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

     public function rsvp(Request $request, $seat_category, $token)
     {
         $attendee = Attendee::where('token', $token)->firstOrFail();

         // Check if attendee has already responded
         if (in_array($attendee->status, ['accepted', 'rejected'])) {
             return redirect()->route('already.responded', [
                 'seat_category' => $seat_category,
                 'token' => $token
             ])->with('info', 'You have already responded: ' . ucfirst($attendee->status) . '.');
         }

         // Validate the seat category
         if ($attendee->seat_category !== $seat_category) {
             abort(404);
         }

         // Validate the action input
         $action = $request->input('action');
         if (!in_array($action, ['accept', 'reject'])) {
             return redirect()->route('rsvp.show', [
                 'seat_category' => $seat_category,
                 'token' => $token
             ])->with('error', 'Invalid action.');
         }

         // Update RSVP status
         $attendee->status = $action === 'accept' ? 'accepted' : 'rejected';
         $attendee->save();

         return redirect()->route('rsvp.show', [
             'seat_category' => $seat_category,
             'token' => $token
         ])->with('success', 'RSVP updated successfully.');
     }


     public function alreadyResponded($seat_category, $token)
     {
         $attendee = Attendee::where('token', $token)->firstOrFail();

         // Ensure the user has actually responded before showing this page
         if (!in_array($attendee->status, ['accepted', 'rejected'])) {
             return redirect()->route('rsvp.show', [
                 'seat_category' => $seat_category,
                 'token' => $token
             ])->with('error', 'No response found. Please RSVP.');
         }

         return view('rsvp.already-responded', compact('attendee'));
     }



}
