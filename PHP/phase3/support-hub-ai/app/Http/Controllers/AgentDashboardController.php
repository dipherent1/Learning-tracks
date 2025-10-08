<?php

namespace App\Http\Controllers;

use App\Models\Department; // <-- Add this import
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgentDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get filter values from the request's query string.
        $filters = $request->only('status', 'department_id');

        // 2. Start building the query.
        $ticketsQuery = Ticket::query()
            ->with(['user', 'department', 'team'])
            ->latest()
            // Conditionally apply filters using the 'when' method.
            ->when($request->input('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->input('department_id'), function ($query, $departmentId) {
                $query->where('department_id', $departmentId);
            });

        // 3. Paginate the results.
        // `withQueryString()` is crucial to make pagination links include the filters.
        $tickets = $ticketsQuery->paginate(15)->withQueryString();

        // 4. Return the view with all the necessary data.
        return Inertia::render('Agent/Dashboard', [
            'tickets' => $tickets,
            'filters' => $filters, // Pass the current filters back to the view
            'departments' => Department::all(['id', 'name']), // Pass all departments for the dropdown
        ]);

    }

    public function myTickets(Request $request)
    {
        $filters = $request->only('status', 'department_id');

        $ticketsQuery = Ticket::query()
            // THE KEY DIFFERENCE: Start by filtering for tickets assigned to the current user.
            ->where('agent_id', auth()->id())
            ->with(['user', 'department', 'team'])
            ->latest()
            // The rest of the filtering logic is exactly the same and reusable.
            ->when($request->input('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->input('department_id'), function ($query, $departmentId) {
                $query->where('department_id', $departmentId);
            });

        $tickets = $ticketsQuery->paginate(15)->withQueryString();

        // We will REUSE the same Vue component, but pass it a different title.
        return Inertia::render('Agent/Dashboard', [
            'tickets' => $tickets,
            'filters' => $filters,
            'departments' => Department::all(['id', 'name']),
            'pageTitle' => 'My Assigned Tickets', // <-- Pass a custom title
        ]);
    }

}