<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = "./app/Models";

        if ($handle = opendir($path)) {
            while (($file = readdir($handle)) !== false) {
                if ($file[0] !== '.' && $file !== 'ResourceModel.php') {
                    $file = Str::lower(Str::before($file, '.'));
                    Permission::CreateBatch([
                        "$file.index" => "List all $file",
                        "$file.show" => "Get specific $file",
                        "$file.update" => "Modify specific $file",
                        "$file.delete" => "Delete specific $file"
                    ]);
                }
            }
            closedir($handle);
        }
    }
}
