<?php

namespace Database\Seeders;

use App\Enums\TwoFactorProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseEnumsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->updateUserTwoFactorProvider();
    }

    protected function updateUserTwoFactorProvider()
    {
        $str_options = $this->strColls(TwoFactorProvider::toArray());
        $sql = "ALTER TABLE users CHANGE COLUMN two_factor_provider two_factor_provider ENUM({$str_options})";
        DB::statement($sql);
        echo "Query: $sql \n";
    }

    protected function strColls($options): string
    {
        return "'" . implode("', '", $options) . "'";
    }
}
