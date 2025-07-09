<?php

namespace App\Http\Controllers;

use App\Enums\RolesEnum;
use App\Services\Dashboard\DashboardService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller {
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index() {
        $user = Auth::user();

        if ($user->hasRole(RolesEnum::REGISTERED_USER->value)) {
            return Inertia::render('dashboard-simple');
        }

        return Inertia::render('dashboard', [
            'stats' => $this->dashboardService->getStats(),
        ]);
    }
}
