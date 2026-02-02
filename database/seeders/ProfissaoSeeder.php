<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ProfissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $papeis = [
            'Orientador Estudantil',
            'Psicologo',
            'Assistente Social',
            'Professor Conselheiro',
            'Diretor',
            'Coordenador',
        ];

        foreach ($papeis as $papel) {
            Role::firstOrCreate([
                'name' => $papel,
                'guard_name' => 'web',
            ]);
        }

        $this->command->info('Papeis/profissoes criados com sucesso!');
    }
}
