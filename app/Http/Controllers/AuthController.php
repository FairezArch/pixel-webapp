<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ModelHasRole;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('sso.login');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(AuthRequest $request)
    {

        if (Auth::attempt($request->only('email', 'password'))) {
            if (!$this->IsAPI()) {
                $user = User::firstWhere('email', $request->email);
                $role = ModelHasRole::firstWhere('model_id', $user->id);
                if ($role->role_id == 3 || $role->role_id == 4) {
                    return redirect()->route('auth.login');
                }
            }
            if ($this->isAPI()) {
                $user = $request->user();

                $storeUser = Store::find($user->store_id);
                if (empty($storeUser)) {
                    return $this->fail('Please add store in this user.', [], 400);
                }

                $searchData = User::where('device_id', $request->device_id)->first();

                if (empty($user->device_id) && empty($searchData)) {
                    $user->device_id = $request->device_id;
                    $user->save();
                }

                if ($searchData && $searchData->id !== $user->id) {
                    return $this->fail('Device already used.', [], 400);
                }

                 if ($user->device_id != $request->device_id) {
                        return $this->fail('You login with different device.', [], 400);
                }

                $modelRole = ModelHasRole::where('model_id', $user->id)->first();
                $user->store;
                $user->job = Role::find($modelRole->role_id)->name;
                return $this->success($user, [
                    'path' => asset("/storage/employee/"),
                    'token' => $user->createToken('myAppToken')->plainTextToken,
                ]);
            }
            $request->session()->regenerate();
            return redirect()->route('dashboard.index');
        }

        if ($this->isAPI())
            return $this->fail('Your credential does not match.', [], 401);
        return redirect()->to(route('auth.login'))->withErrors([
            'email' => __('auth.failed')
        ])->withInput();
    }

    public function forgot(Request $request)
    {
        return response()->json([
            'message' => 'Email / Password Salah',
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($this->isAPI()) {
            $user->currentAccessToken()->delete();
            return $this->success();
        }

        auth()->logout();

        return redirect()->route('auth.login');
    }

    public function getENV()
    {
        return response()->download(public_path('thunder-client-json/thunder-environment_pixel_postman.json'));
    }

    public function getCollection()
    {
        return response()->download(public_path('thunder-client-json/thunder-collection_PIXEL_postman.json'));
    }
}
