<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // create permissions
        $permissions = [
            'Dashboard' => ['dashboard'],
            'Kategori Produk' =>  [ 
                'category_product_list',
                'category_product_create',
                'category_product_update',
                'category_product_delete'
            ],
            'Produk' =>  [ 
                'product_list',
                'product_create',
                'product_update',
                'product_delete'
            ],
            'Penjualan' =>  [ 
                'sales_list',
                'sales_create'
            ],
            'Karyawan' =>  [ 
                'employee_list',
                'employee_create',
                'employee_update',
                'employee_delete'
            ],
            'Jam Hadir' =>  [ 
                'attendance_list',
                'attendance_create',
            ],
            'Target' =>  [ 
                'target_list',
                'target_create',
                'target_update',
                'target_delete'
            ],
            'Daerah' =>  [ 
                'region_list',
                'region_create',
                'region_update',
                'region_delete'
            ],
            'Toko Utama' =>  [ 
                'main_shop_list',
                'main_shop_create',
                'main_shop_update',
                'main_shop_delete'
            ],
            'Toko Cabang' =>  [ 
                'branch_shop_list',
                'branch_shop_create',
                'branch_shop_update',
                'branch_shop_delete'
            ],
            'Peran & Hak Akses' =>  [ 
                'role_permission_list',
                'role_permission_create',
                'role_permission_update'
            ],
            'Laporan Penjualan' =>  [ 
                'sales_report_list',                
            ],
            'Laporan Pembelian' =>  [ 
                'buyer_report_list',                
            ],
            'Laporan Jam Sibuk' =>  [ 
                'rush_hour_report_list',                
            ],
            'Laporan Target' =>  [ 
                'target_report_list',                
            ]
        ];

        $res = [];
        foreach ($permissions as $permission => $values) {
            foreach($values as $value){
                $res[] = ['name' => $value, 'guard_name' => 'web', 'name_menu' => $permission];
            }
        }

        Permission::insert($res);

        $role = Role::create(['name' => "super-admin"]);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => "toko"]);
        $role->givePermissionTo('dashboard');
        $role = Role::create(['name' => "promotor"]);
        $role->givePermissionTo('dashboard');
        $role = Role::create(['name' => "frontliner"]);
        $role->givePermissionTo('dashboard');
    }
}
