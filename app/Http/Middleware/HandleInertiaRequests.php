<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use Faker\Test\Provider\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        return array_merge(parent::share($request), [
            'user' => Auth::check() ? Auth::user() : null,
            'personal_info' => Auth::check() ? Auth::user()->personal_info : null,
            'role' => Auth::check() ? Auth::user()->role : null,
            'notifications' => Auth::check() ? $this->getNotifications() : null,
        ]);
    }

    protected function getNotifications()
    {
        if (Auth::user()->role_id === 3) {
            return Notification::where('is_admin', 0)->where('is_read', 0)->where('user_id', Auth::user()->id)->orderBy('created_at','desc')->get();
        }
        return  Notification::where('is_admin', 1)->where('is_read', 0)->orderBy('created_at','desc')->get();
    }
}
