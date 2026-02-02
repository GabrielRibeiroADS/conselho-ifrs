<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Definir todas as permissões
        $permissions = [
            // Unidades
            'unidades.index',
            'unidades.create',
            'unidades.edit',
            'unidades.delete',
            
            // Cursos
            'cursos.index',
            'cursos.create',
            'cursos.edit',
            'cursos.delete',
            
            // Usuários
            'usuarios.index',
            'usuarios.create',
            'usuarios.edit',
            'usuarios.delete',

            // Papéis/Profissões
            'papeis.index',
            'papeis.create',
            'papeis.edit',
            'papeis.delete',
            
            // Estudantes
            'estudantes.index',
            'estudantes.create',
            'estudantes.edit',
            'estudantes.delete',
            'estudantes.show',
            
            // Análise Socioeconômica
            'analisesocio.index',
            'analisesocio.create',
            'analisesocio.edit',
            'analisesocio.delete',
            
            // Complementos de Análise
            'complementos.index',
            'complementos.create',
            'complementos.edit',
            'complementos.delete',
            
            // Recursos de Análise
            'recursos.index',
            'recursos.create',
            'recursos.edit',
            'recursos.delete',
            
            // Mapeamentos (Censo)
            'mapeamentos.index',
            'mapeamentos.create',
            'mapeamentos.edit',
            'mapeamentos.delete',

            // Conselhos
            'conselhos.index',
            'conselhos.create',
            'conselhos.edit',
            'conselhos.delete',
            'conselhos.show',
            
            // Relatórios
            'relatorios.index',
            'relatorios.export',
        ];

        // Criar permissões
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Criar roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $coordRole = Role::firstOrCreate(['name' => 'coordenador', 'guard_name' => 'web']);
        $analistaRole = Role::firstOrCreate(['name' => 'analista', 'guard_name' => 'web']);

        // Admin tem todas as permissões
        $adminRole->syncPermissions($permissions);

        // Coordenador tem permissões de gerenciamento
        $coordRole->syncPermissions([
            'unidades.index',
            'cursos.index', 'cursos.create', 'cursos.edit', 'cursos.delete',
            'usuarios.index', 'usuarios.create', 'usuarios.edit', 'usuarios.delete',
            'papeis.index',
            'estudantes.index', 'estudantes.create', 'estudantes.edit', 'estudantes.delete', 'estudantes.show',
            'analisesocio.index', 'analisesocio.create', 'analisesocio.edit', 'analisesocio.delete',
            'complementos.index', 'complementos.create', 'complementos.edit', 'complementos.delete',
            'recursos.index', 'recursos.create', 'recursos.edit', 'recursos.delete',
            'mapeamentos.index', 'mapeamentos.create', 'mapeamentos.edit', 'mapeamentos.delete',
            'conselhos.index', 'conselhos.create', 'conselhos.edit', 'conselhos.delete', 'conselhos.show',
            'relatorios.index', 'relatorios.export',
        ]);

        // Analista tem permissões de análise
        $analistaRole->syncPermissions([
            'estudantes.index', 'estudantes.show',
            'analisesocio.index', 'analisesocio.create', 'analisesocio.edit',
            'complementos.index', 'complementos.create', 'complementos.edit',
            'recursos.index', 'recursos.create', 'recursos.edit',
            'relatorios.index',
        ]);

        $this->command->info('Permissões e roles criados com sucesso!');
    }
}
