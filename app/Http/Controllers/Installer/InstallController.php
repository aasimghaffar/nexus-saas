<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Support\Installer;
use App\Support\InstallRunner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstallController extends Controller
{
    public function show()
    {
        if (Installer::isInstalled()) {
            return redirect('/');
        }

        return view('installer.index', [
            'requirements' => Installer::requirements(),
            'errors'       => [],
            'old'          => [],
        ]);
    }

    public function run(Request $request)
    {
        if (Installer::isInstalled()) {
            return redirect('/');
        }

        $validator = Validator::make($request->all(), [
            'app_name'       => ['required', 'string', 'max:100'],
            'app_url'        => ['required', 'url'],
            'db_connection'  => ['required', 'in:mysql,sqlite'],
            'db_host'        => ['required_if:db_connection,mysql', 'nullable', 'string'],
            'db_port'        => ['required_if:db_connection,mysql', 'nullable', 'numeric'],
            'db_database'    => ['required_if:db_connection,mysql', 'nullable', 'string'],
            'db_username'    => ['required_if:db_connection,mysql', 'nullable', 'string'],
            'db_password'    => ['nullable', 'string'],
            'admin_name'     => ['required', 'string', 'max:100'],
            'admin_email'    => ['required', 'email', 'max:255'],
            'admin_password' => ['required', 'string', 'min:8'],
            'demo_data'      => ['nullable'],
        ]);

        if ($validator->fails()) {
            return view('installer.index', [
                'requirements' => Installer::requirements(),
                'errors'       => $validator->errors()->all(),
                'old'          => $request->except('admin_password', 'db_password'),
            ]);
        }

        $data = $validator->validated();
        $data['demo_data'] = $request->boolean('demo_data');

        try {
            InstallRunner::run($data, deferEnvWrite: true);
        } catch (\Throwable $e) {
            InstallRunner::log('FAILED: '.$e->getMessage());

            return view('installer.index', [
                'requirements' => Installer::requirements(),
                'errors'       => [
                    'Installation failed: '.$e->getMessage(),
                    'Details were written to storage/logs/installer.log — nothing is broken; fix the cause and submit again.',
                ],
                'old'          => $request->except('admin_password', 'db_password'),
            ]);
        }

        return view('installer.done', ['email' => $data['admin_email']]);
    }
}
