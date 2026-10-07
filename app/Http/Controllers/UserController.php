<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // index
    public function index(Request $request)
    {
        $users = User::query()->when($request->filled('keyword'), function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->keyword}%")
                    ->orWhere('email', 'like', "%{$request->keyword}%");
            });
        })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('pages.users.index', compact('users'));
    }

    // create
    public function create()
    {
        return view('pages.users.create');
    }

    // store
    public function store(UserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    // edit
    public function edit(User $user)
    {
        return view('pages.users.edit', compact('user'));
    }

    // update
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        if (blank($validated['phone'] ?? null)) {
            unset($validated['phone']);
        }

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    //destroy
    public function destroy(Request $request, User $user)
    {
        abort_unless($request->user()->role === 'admin', 403);
        abort_if($request->user()->is($user), 422, 'You cannot delete your own account.');

        $user->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => "User deleted successfully"]);
        }

        return back()->with('success', 'User deleted successfully');
    }
}
