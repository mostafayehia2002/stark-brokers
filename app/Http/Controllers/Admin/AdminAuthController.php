<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginAdminRequest;
use App\Services\Admin\AdminAuthService;

class AdminAuthController extends Controller
{
    //
    protected AdminAuthService $adminAuthService;
    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    public function login(LoginAdminRequest $request){

        $response = $this->adminAuthService->login($request);
        if($response['success']){
            toastr()->success($response['message']);
            return redirect()->route('admin.dashboard');
        }
        toastr()->error($response['message']);
        return redirect()->back()->withInput($request->only('login', 'password', 'remember'));
    }

    public function logout(){
       $response= $this->adminAuthService->logout();
       if($response['success']){
           toastr()->success($response['message']);

           return redirect()->route('login');
       }
        toastr()->error($response['message']);
    }
}
