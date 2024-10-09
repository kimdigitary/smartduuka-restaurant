<?php

namespace App\Services;


use App\Enums\Ask;
use App\Enums\Role;
use App\Enums\Status;
use App\Http\Requests\TenantFileTextGetRequest;
use App\Libraries\AppLibrary;
use App\Models\TenantUser;
use App\Models\User;
use Exception;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\TenantRequest;
use App\Http\Requests\PaginateRequest;
use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\MailerSend;
use Smartisan\Settings\Facades\Settings;


class TenantService
{
    protected $tenantFilter = [
        'name',
        'email',
        'status',
        'username'
    ];
    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return Tenant::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->tenantFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(TenantRequest $request)
    {
        try {
            $createTenant = Tenant::create($request->validated());

            // after creating tenant proceed to create an admi and assign a role to that admin
            $admin      = User::create([
                'name'              => 'Admin',
                'email'             => $createTenant->email,
                'username'          => $createTenant->username,
                'email_verified_at' => now(),
                'password'          => bcrypt('123456'), //\Str::random(8)
                'branch_id'         => 0,
                'status'            => Status::ACTIVE,
                'country_code'      => '+256',
                'is_guest'          => Ask::NO
            ]);

            $admin->assignRole(Role::ADMIN);

            // add to tenant
            TenantUser::create([
                'tenant_id' => $createTenant->id,
                'user_id' => $admin->id
            ]);

            // send email to tenant admin
            $mailerSend = new MailerSend(['api_key' => config('mail.mail_send.api_key')]);

            $recipient = [
                new Recipient($admin->email, 'Recipient'),
            ];

            $emailParams = (new EmailParams())
                ->setFrom('MS_H0p2BB@trial-351ndgw25pxgzqx8.mlsender.net')
                ->setFromName('Smart Duuka')
                ->setRecipients($recipient)
                ->setSubject('New Account Creation')
                ->setHtml('This is the HTML content')
                ->setText('This is the text content');



            $mailerSend->email->send($emailParams);

            return $createTenant;

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, $tenant): Tenant
    {
        try {
            return $tenant->update($request->validated());

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy($tenant): void
    {
        try {
            Tenant::find($tenant)->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show($tenant): Tenant
    {
        try {
            return Tenant::findOrFail($tenant);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

}