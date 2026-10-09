<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FoundationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Full system access and control.'],
            ['name' => 'Marketing & Sales Manager', 'slug' => 'sales_manager', 'description' => 'Manages leads, follow-ups, quotations, sales, and customers.'],
            ['name' => 'Project Manager', 'slug' => 'project_manager', 'description' => 'Manages orders, projects, tasks, team allocation, QA and delivery.'],
            ['name' => 'Web Developer', 'slug' => 'web_developer', 'description' => 'Works on assigned web projects, tasks, submits work logs and fixes tickets.'],
            ['name' => 'Mobile App Developer', 'slug' => 'mobile_app_developer', 'description' => 'Works on assigned mobile app projects, tasks, and work logs.'],
            ['name' => 'Video Editor', 'slug' => 'video_editor', 'description' => 'Handles video production, tracks duration in seconds, logs and submissions.'],
            ['name' => 'Graphics Designer', 'slug' => 'graphics_designer', 'description' => 'Handles creative branding, UI/UX, and graphic design tasks.'],
            ['name' => 'Operator', 'slug' => 'operator', 'description' => 'General data entry, lead logging, and customer support assistance.'],
        ];

        $roleModels = [];
        foreach ($roles as $r) {
            $roleModels[$r['slug']] = Role::updateOrCreate(['slug' => $r['slug']], $r);
        }

        // 2. Permissions
        $permissions = [
            // CRM
            ['name' => 'View Leads', 'slug' => 'leads.view', 'module' => 'crm'],
            ['name' => 'Create Leads', 'slug' => 'leads.create', 'module' => 'crm'],
            ['name' => 'Edit Leads', 'slug' => 'leads.edit', 'module' => 'crm'],
            ['name' => 'Delete Leads', 'slug' => 'leads.delete', 'module' => 'crm'],
            ['name' => 'Assign Leads', 'slug' => 'leads.assign', 'module' => 'crm'],
            ['name' => 'View Customers', 'slug' => 'customers.view', 'module' => 'crm'],
            ['name' => 'Manage Customers', 'slug' => 'customers.manage', 'module' => 'crm'],
            ['name' => 'Create Quotations', 'slug' => 'quotations.create', 'module' => 'crm'],
            ['name' => 'Manage Quotations', 'slug' => 'quotations.manage', 'module' => 'crm'],

            // Projects & Orders
            ['name' => 'View Orders', 'slug' => 'orders.view', 'module' => 'projects'],
            ['name' => 'Create Orders', 'slug' => 'orders.create', 'module' => 'projects'],
            ['name' => 'View Projects', 'slug' => 'projects.view', 'module' => 'projects'],
            ['name' => 'Manage Projects', 'slug' => 'projects.manage', 'module' => 'projects'],
            ['name' => 'View Tasks', 'slug' => 'tasks.view', 'module' => 'projects'],
            ['name' => 'Manage Tasks', 'slug' => 'tasks.manage', 'module' => 'projects'],
            ['name' => 'Perform QA', 'slug' => 'tasks.qa', 'module' => 'projects'],

            // Team & Work
            ['name' => 'View Employees', 'slug' => 'employees.view', 'module' => 'team'],
            ['name' => 'Manage Employees', 'slug' => 'employees.manage', 'module' => 'team'],
            ['name' => 'Log Work', 'slug' => 'work_logs.create', 'module' => 'team'],
            ['name' => 'View Video Billing', 'slug' => 'video_billing.view', 'module' => 'team'],
            ['name' => 'Manage Video Billing', 'slug' => 'video_billing.manage', 'module' => 'team'],

            // Support
            ['name' => 'View Support Tickets', 'slug' => 'tickets.view', 'module' => 'support'],
            ['name' => 'Manage Support Tickets', 'slug' => 'tickets.manage', 'module' => 'support'],
            ['name' => 'Resolve Support Tickets', 'slug' => 'tickets.resolve', 'module' => 'support'],

            // Finance
            ['name' => 'View Payments', 'slug' => 'payments.view', 'module' => 'finance'],
            ['name' => 'Record Payments', 'slug' => 'payments.record', 'module' => 'finance'],
            ['name' => 'Manage Expenses', 'slug' => 'expenses.manage', 'module' => 'finance'],
            ['name' => 'Manage Renewals', 'slug' => 'renewals.manage', 'module' => 'finance'],
            ['name' => 'View Financial Reports', 'slug' => 'reports.financial', 'module' => 'finance'],

            // Settings
            ['name' => 'Manage Settings', 'slug' => 'settings.manage', 'module' => 'settings'],
        ];

        $permModels = [];
        foreach ($permissions as $p) {
            $permModels[$p['slug']] = Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 3. Attach permissions to roles
        // Admin gets all
        $roleModels['admin']->permissions()->sync(array_values(array_map(fn($p) => $p->id, $permModels)));

        // Sales Manager
        $salesPerms = [
            'leads.view', 'leads.create', 'leads.edit', 'leads.assign',
            'customers.view', 'customers.manage',
            'quotations.create', 'quotations.manage',
            'orders.view', 'orders.create',
            'payments.record',
        ];
        $roleModels['sales_manager']->permissions()->sync(
            Permission::whereIn('slug', $salesPerms)->pluck('id')
        );

        // Project Manager
        $pmPerms = [
            'orders.view', 'projects.view', 'projects.manage',
            'tasks.view', 'tasks.manage', 'tasks.qa',
            'employees.view', 'work_logs.create',
            'video_billing.view', 'video_billing.manage',
            'tickets.view', 'tickets.manage', 'tickets.resolve',
        ];
        $roleModels['project_manager']->permissions()->sync(
            Permission::whereIn('slug', $pmPerms)->pluck('id')
        );

        // Developers
        $devPerms = [
            'projects.view', 'tasks.view', 'work_logs.create',
            'tickets.view', 'tickets.resolve'
        ];
        $roleModels['web_developer']->permissions()->sync(
            Permission::whereIn('slug', $devPerms)->pluck('id')
        );
        $roleModels['mobile_app_developer']->permissions()->sync(
            Permission::whereIn('slug', $devPerms)->pluck('id')
        );

        // Video Editor
        $videoPerms = [
            'tasks.view', 'work_logs.create', 'video_billing.view'
        ];
        $roleModels['video_editor']->permissions()->sync(
            Permission::whereIn('slug', $videoPerms)->pluck('id')
        );

        // Graphics Designer
        $roleModels['graphics_designer']->permissions()->sync(
            Permission::whereIn('slug', ['tasks.view', 'work_logs.create'])->pluck('id')
        );

        // Operator
        $roleModels['operator']->permissions()->sync(
            Permission::whereIn('slug', ['leads.view', 'leads.create', 'customers.view', 'tickets.view'])->pluck('id')
        );

        // 4. Default Admin User
        User::updateOrCreate(
            ['email' => 'admin@projuktisheba.com'],
            [
                'name' => 'Projukti Sheba Admin',
                'role_id' => $roleModels['admin']->id,
                'phone' => '+8801700000000',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 5. Default Settings
        $defaultSettings = [
            ['key' => 'company_name', 'value' => 'Projukti Sheba', 'group' => 'company', 'type' => 'string', 'description' => 'Company Name'],
            ['key' => 'company_phone', 'value' => '+8801700000000', 'group' => 'company', 'type' => 'string', 'description' => 'Primary Phone'],
            ['key' => 'company_email', 'value' => 'info@projuktisheba.com', 'group' => 'company', 'type' => 'string', 'description' => 'Primary Email'],
            ['key' => 'currency_symbol', 'value' => '৳', 'group' => 'general', 'type' => 'string', 'description' => 'Default Currency Symbol'],
            ['key' => 'currency_code', 'value' => 'BDT', 'group' => 'general', 'type' => 'string', 'description' => 'Default Currency Code'],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka', 'group' => 'general', 'type' => 'string', 'description' => 'Application Timezone'],
            ['key' => 'default_video_rate_per_sec', 'value' => '8.00', 'group' => 'finance', 'type' => 'decimal', 'description' => 'Default rate per second for video editing in BDT'],
            ['key' => 'lead_sources', 'value' => json_encode(['Website Form', 'WhatsApp', 'Facebook/Instagram Ads', 'Google Maps', 'Google Search', 'YouTube', 'LinkedIn', 'Referral', 'Manual Entry']), 'group' => 'crm', 'type' => 'json', 'description' => 'Available lead sources'],
            ['key' => 'service_categories', 'value' => json_encode(['Software Development', 'Website Development', 'Creative & Video Editing', 'Graphics Design', 'Digital Marketing', 'SMO & SMM', 'Ads Campaign', 'Others']), 'group' => 'sales', 'type' => 'json', 'description' => 'Main service categories'],
            ['key' => 'payment_methods', 'value' => json_encode(['Cash', 'Bank Transfer', 'bKash', 'Nagad', 'Rocket', 'Card/Online']), 'group' => 'finance', 'type' => 'json', 'description' => 'Supported payment channels'],
        ];

        foreach ($defaultSettings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
