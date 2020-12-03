<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Get all User.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(['users' => User::all()], 200);
    }

    /**
     * Get one user.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['user' => $user], 200);

        } catch (Exception $e) {

            return response()->json(['message' => 'user not found!'], 404);
        }

    }
}
