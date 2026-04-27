<?php

namespace App\Http\Controllers\Personalization;

use App\Http\Controllers\Controller;
use App\Models\Recommendation;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function markAsRead(Recommendation $recommendation)
    {
        $user = Auth::user();

        if (!$user || $recommendation->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $recommendation->update([
            'is_read' => true,
        ]);

        return back()->with('success', 'Marked as read.');
    }
}