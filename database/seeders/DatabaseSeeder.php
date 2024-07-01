<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
//        Schema::disableForeignKeyConstraints();
//        DB::table('permissions')->truncate();
//        DB::table('model_has_permissions')->truncate();
//        DB::table('model_has_permissions')->truncate();
//        DB::table('model_has_roles')->truncate();
//        DB::table('permissions')->truncate();
//        DB::table('roles')->truncate();
//        DB::table('role_has_permissions')->truncate();

//        DB::table('menus')->truncate();
//        DB::table('addons')->truncate();
//        DB::table('addresses')->truncate();
//        DB::table('analytics')->truncate();
//        DB::table('analytic_sections')->truncate();
//        DB::table('dining_tables')->truncate();
//        DB::table('expenses')->truncate();
//        DB::table('expense_categories')->truncate();
//        DB::table('expense_payments')->truncate();
//        DB::table('items')->truncate();
//        DB::table('item_addons')->truncate();
//        DB::table('item_attributes')->truncate();
//        DB::table('item_categories')->truncate();
//        DB::table('item_extras')->truncate();
//        DB::table('item_variations')->truncate();
//        DB::table('languages')->truncate();
//        DB::table('media')->truncate();
//        DB::table('menu_sections')->truncate();
//        DB::table('menu_templates')->truncate();
//        DB::table('notification_alerts')->truncate();
//        DB::table('offers')->truncate();
//        DB::table('offer_items')->truncate();
//        DB::table('orders')->truncate();
//        DB::table('order_addresses')->truncate();
//        DB::table('order_items')->truncate();
//        DB::table('pages')->truncate();
//        DB::table('settings')->truncate();
//        DB::table('taxes')->truncate();
//        DB::table('users')->truncate();

//        Schema::enableForeignKeyConstraints();

        $this->call(MenuTableSeeder::class);
        $this->call(MenuTemplateTableSeeder::class);
        $this->call(MenuSectionTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(SiteTableSeeder::class);
//        $this->call(NotificationTableSeeder::class);
//        $this->call(NotificationAlertTableSeeder::class);
//        $this->call(PaymentGatewayTableSeederVersionOne::class);
//        $this->call(PaymentGatewayTableSeederVersionTwo::class);
//        $this->call(SmsGatewayTableSeederVersionOne::class);
//        $this->call(SmsGatewayTableSeederVersionTwo::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(CurrencyTableSeeder::class);
//        $this->call(BranchTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(RolePermissionTableSeeder::class);
//        $this->call(MailTableSeeder::class);
        $this->call(OrderSetupTableSeeder::class);
//        $this->call(OtpTableSeeder::class);
        $this->call(ThemeTableSeeder::class);
//        $this->call(LicenseTableSeeder::class);
//        $this->call(SocialMediaTableSeeder::class);
//        $this->call(AnalyticTableSeeder::class);
        $this->call(TaxTableSeeder::class);
        $this->call(PageTableSeeder::class);
//        $this->call(ItemCategoryTableSeeder::class);
//        $this->call(ItemAttributeTableSeeder::class);
//        $this->call(PaymentGatewayDataTableSeeder::class);
//        $this->call(ItemTableSeeder::class);
//        $this->call(ItemVariationTableSeeder::class);
//        $this->call(ItemExtraTableSeeder::class);
//        $this->call(ItemAddonTableSeeder::class);
//        $this->call(OfferTableSeeder::class);
//        $this->call(OfferItemTableSeeder::class);
//        $this->call(OrderTableSeeder::class);
//        $this->call(OrderItemTableSeeder::class);
//        $this->call(OrderAddressTableSeeder::class);
//        $this->call(TransactionTableSeeder::class);
//        $this->call(DiningTableTableSeeder::class);
    }
}
