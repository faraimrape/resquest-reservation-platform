<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        $properties = Property::with(['rooms' => function ($query) {
            $query->whereHas('property', function ($query) {
                $query->where('is_published', true);
            });
        }])->where('is_published', true);

        if ($search) {
            $properties->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhereHas('rooms', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $properties = $properties->paginate(5);

        return Inertia::render('Home', [
            'properties' => $properties,
            'filters' => ['search' => $search],
        ]);
    }


}
