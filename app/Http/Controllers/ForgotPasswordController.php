<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Routing\Controller as BaseController;

class ForgotPasswordController extends BaseController
{
    use SendsPasswordResetEmails;
}
