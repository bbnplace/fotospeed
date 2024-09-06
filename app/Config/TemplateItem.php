<?php

namespace App\Config;
use App\Models\Process;

class TemplateItem
{
    public static function usage(): array
    {
        return array_merge([
            'Signup OTP',
            'Signup Confirmation',
            'Password Reset OTP',
            'Password Reset Confirmation',
            'Login OTP',
            'Login Confirmation',
        ], Process::getProcessesArray());
    }

    public static function target(): array
    {
        return ['Customers', 'Team'];
    }

    public static function timing(): array
    {
        return ['Before Process', 'After Process'];
    }
}
