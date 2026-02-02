@php
    $usuario = auth()->user();
@endphp

<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" id="sidebarMenu">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            {{-- Dashboard --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}" 
                   href="{{ route('admin.home') }}">
                    <i class="bi bi-house-door"></i> Início
                </a>
            </li>
            
            {{-- Análises Socioeconômicas --}}
            @if($usuario->podeAnaliseSocio() || $usuario->podeComplementoAnaliseSocio() || $usuario->podeRecursoAnaliseSocio())
            <li class="nav-item">
                <a class="nav-link" href="#submenuAnalises" data-toggle="collapse" aria-expanded="false">
                    <i class="bi bi-file-earmark-text"></i> Auxílios
                    <i class="bi bi-chevron-down float-right"></i>
                </a>
                <div class="collapse" id="submenuAnalises">
                    <ul class="nav flex-column ml-3">
                        @if($usuario->podeAnaliseSocio())
                        <li class="nav-item">
                            <a class="nav-link" href="#">Análises Socioeconômicas</a>
                        </li>
                        @endif
                        @if($usuario->podeComplementoAnaliseSocio())
                        <li class="nav-item">
                            <a class="nav-link" href="#">Complementos</a>
                        </li>
                        @endif
                        @if($usuario->podeRecursoAnaliseSocio())
                        <li class="nav-item">
                            <a class="nav-link" href="#">Recursos</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            
            {{-- Mapeamentos --}}
            @if($usuario->podeAnaliseCenso())
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-bar-chart"></i> Mapeamentos
                </a>
            </li>
            @endif

            {{-- Conselhos --}}
            @if($usuario->isAdmin() || $usuario->isCoordUnidade() || auth()->user()->can('conselhos.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.conselhos.*') ? 'active' : '' }}" 
                   href="{{ route('admin.conselhos.index') }}">
                    <i class="bi bi-journal-text"></i> Conselhos
                </a>
            </li>
            @endif
            
            {{-- Coordenação --}}
            @if($usuario->isCoordUnidade() || $usuario->isAdmin())
            <li class="nav-item">
                <a class="nav-link" href="#submenuCoordenacao" data-toggle="collapse" aria-expanded="false">
                    <i class="bi bi-people"></i> Coordenação
                    <i class="bi bi-chevron-down float-right"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.estudantes.*') || request()->routeIs('admin.usuarios.*') ? 'show' : '' }}" id="submenuCoordenacao">
                    <ul class="nav flex-column ml-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.estudantes.*') ? 'active' : '' }}" 
                               href="{{ route('admin.estudantes.index') }}">
                                Estudantes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}" 
                               href="{{ route('admin.usuarios.index') }}">
                                Usuários
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            
            {{-- Administração --}}
            @if($usuario->isAdmin())
            <li class="nav-item">
                <a class="nav-link" href="#submenuAdmin" data-toggle="collapse" aria-expanded="false">
                    <i class="bi bi-gear"></i> Administração
                    <i class="bi bi-chevron-down float-right"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.unidades.*') || request()->routeIs('admin.cursos.*') || request()->routeIs('admin.papeis.*') ? 'show' : '' }}" id="submenuAdmin">
                    <ul class="nav flex-column ml-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.unidades.*') ? 'active' : '' }}" 
                               href="{{ route('admin.unidades.index') }}">
                                Unidades
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.cursos.*') ? 'active' : '' }}" 
                               href="{{ route('admin.cursos.index') }}">
                                Cursos
                            </a>
                        </li>
                        @if($usuario->isAdmin() || auth()->user()->can('papeis.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.papeis.*') ? 'active' : '' }}" 
                               href="{{ route('admin.papeis.index') }}">
                                Papéis/Profissões
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
        </ul>
    </div>
</nav>
