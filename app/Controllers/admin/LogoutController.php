<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class LogoutController extends BaseController
{
    public function index()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/admin');
    }
}
