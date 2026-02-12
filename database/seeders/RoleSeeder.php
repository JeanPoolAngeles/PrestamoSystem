<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Gerente']);

        Permission::create(['name' => 'admin.home', 'description' => 'ver panel de control'])->syncRoles([$role1]);

        Permission::create(['name' => 'admin.usuarios.index', 'description' => 'ver listado de usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.usuarios.edit', 'description' => 'asignar un rol'])->syncRoles([$role1]);

        Permission::create(['name' => 'admin.clientes.index', 'description' => 'ver listado de clientes'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.clientes.create', 'description' => 'ver listado de clientes'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.clientes.edit', 'description' => 'ver listado de clientes'])->assignRole($role1);
        Permission::create(['name' => 'admin.clientes.delete', 'description' => 'ver listado de clientes'])->assignRole($role1);
        Permission::create(['name' => 'admin.clientes.reportes', 'description' => 'ver listado de clientes'])->assignRole($role1);

        Permission::create(['name' => 'admin.prestamos.index', 'description' => 'ver listado de prestamos'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.prestamos.reportes', 'description' => 'reportes de prestamos'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.prestamos.anular', 'description' => 'anular prestamos'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.prestamos.store', 'description' => 'crear prestamos'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.prestamos.edit', 'description' => 'editar prestamos'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.prestamos.update', 'description' => 'update prestamos'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.prestamos.show', 'description' => 'ver prestamos'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.prestamos.ticket', 'description' => 'ver ticket de prestamos'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.prestamos.validar', 'description' => 'validar prestamos'])->syncRoles([$role1]);

        Permission::create(['name' => 'admin.formas-pago.index', 'description' => 'ver forma de pagos'])->assignRole($role1);
        Permission::create(['name' => 'admin.formas-pago.create', 'description' => 'crear forma de pago'])->assignRole($role1);
        Permission::create(['name' => 'admin.formas-pago.edit', 'description' => 'editar forma de pago'])->assignRole($role1);
        Permission::create(['name' => 'admin.formas-pago.delete', 'description' => 'borrar forma de pago'])->assignRole($role1);

        Permission::create(['name' => 'admin.cotizacion.index', 'description' => 'ver listado de cotizacion'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.cotizacion.show', 'description' => 'ver show de cotizacion'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.cotizacion.eliminar', 'description' => 'eliminar cotizacion'])->assignRole($role1);
        Permission::create(['name' => 'admin.cotizacion.reportes', 'description' => 'ver reportes de cotizacion'])->assignRole($role1);

        Permission::create(['name' => 'admin.creditoclientes.index', 'description' => 'ver listado de creditoclientes'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.creditoclientes.abonos', 'description' => 'ver abonos de creditoclientes'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.creditoclientes.reportes', 'description' => 'ver reportes de creditoclientes'])->assignRole($role1);

        Permission::create(['name' => 'admin.compania.index', 'description' => 'ver compañia'])->assignRole($role1);
        Permission::create(['name' => 'admin.compania.update', 'description' => 'actualizar compañia'])->assignRole($role1);
    }
}
