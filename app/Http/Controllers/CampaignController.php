<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class CampaignController extends Controller
{
    public function index()
    {
        $search = request('search', '');

        $withTrashed = request('withTrashed', false);

        $campaigns = Campaign::query()
            ->when($withTrashed, fn(Builder $query) => $query->withTrashed())
            ->when(
                $search,
                fn(Builder $query) => $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', '=', $search)
            )
            ->paginate(5)
            ->appends(compact('search', 'withTrashed'));

        return view('campaigns.index', [
            'campaigns' => $campaigns,
            'search' => $search,
            'withTrashed' => $withTrashed,
        ]);
    }

    public function create()
    {
        return view('campaigns.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return back()->with('message', __('Campaign successfully deleted!'));
    }

    public function restore(Campaign $campaign)
    {
        $campaign->restore();

        return back()->with('message', __('Campaign successfully restored!'));
    }
}
