<?php
$acesso = session()->get('usuario.acesso');
$setor = session()->get('usuario.setor');
$perfis = session()->get('usuario.perfis');
// dd(session());
// dd($perfis);
?>


<div id="app">
    <nav class="navbar navbar-dark navbar-expand-md navbar-light shadow-sm" style="background-color: #355089;">
        <div class="container">
            <a class="navbar-brand" style="font-weight:bold; font-size: 28px; color:#ffffff;"
               href="{{ route('inicio.val') }}">Júpiter</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDarkdropdown"
                    aria-controls="navbarNavDarkdropdown" aria-expanded="false" aria-label="Toggle navigation"
                    style="border:none; color:#3d5b9c;">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class=" navbar-collapse" id="navbarNavDarkdropdown">
                @if(in_array(1, $perfis) or in_array(2, $perfis) or in_array(3, $perfis) or in_array(4, $perfis) or in_array(5, $perfis) or in_array(6, $perfis))
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="1" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false" style="color:#ffffff;">Área de
                                Pessoal</a>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkdropdownMenuLink">

                                <li><a class="dropdown-item" href="/gerenciar-funcionario">Gerenciar
                                        Funcionarios</a>
                                </li>              

                                <li><a class="dropdown-item" href="/gerenciar-associado">Gerenciar Associados</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                @endif
                @if(in_array(1, $perfis) or in_array(2, $perfis) or in_array(3, $perfis) or in_array(4, $perfis) or in_array(5, $perfis) or in_array(6, $perfis))
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="2" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false" style="color:#ffffff;">Gerenciar</a>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkdropdownMenuLink">

                                <li><a href="{{ route('gerenciar.cargos') }}" class="dropdown-item">
                                        Cargo dos Funcionários</a>
                                </li>


                                <li><a class="dropdown-item" href="/gerenciar-entidades-de-ensino">
                                        Entidades
                                        de
                                        Ensino</a>

                                <li><a class="dropdown-item" href="/gerenciar-setor"> Setores</a></li>

                                <li><a class="dropdown-item" href="/gerenciar-hierarquia">Hierarquia de Setores</a>
                                </li>


                                <li><a class="dropdown-item" href="/controle-vagas">Vagas por Setor</a></li>


                                <li><a href="{{ route('index.tipos-de-contrato') }}" class="dropdown-item">
                                        Tipos
                                        de
                                        Contrato</a>
                                </li>

                                <li><a href="/gerenciar-tipo-desconto" class="dropdown-item"> Tipos de
                                        Desconto</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                @endif

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="2" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false" style="color:#ffffff;">Direitos
                            Remuneratórios</a>

                        <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkdropdownMenuLink">
                            @if (in_array(7, $perfis ?? []))
                                <li><a href="{{ route('IndexGerenciarFerias') }}" class="dropdown-item">Período de
                                        Férias</a>
                                </li>
                            @endif
                            @if (in_array(3, $perfis ?? []))
                                <li><a href="{{ route('AdministrarFerias') }}" class="dropdown-item">Gerenciar
                                        Férias</a>
                                </li>
                            @endif
                            @if (in_array(3, $perfis ?? []) or in_array(3, $perfis ?? []))
                                <li><a href="{{ route('index.gerenciar-dia-limite-ferias') }}"
                                       class="dropdown-item">Dias
                                        limite para as Férias </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                </ul>

                @if(in_array(1, $perfis ?? []) or in_array(6, $perfis ?? []) or in_array(4, $perfis ?? []))
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="1" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false" style="color:#ffffff;">Relatórios</a>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkdropdownMenuLink">
                                @if (in_array(15, $acesso))
                                    <li><a class="dropdown-item" href="/gerenciar-efetivo">Controle de Efetivo</a></li>
                                @endif
                                @if (in_array(18, $acesso))
                                    <li><a class="dropdown-item" href="/controle-ferias">Controle de Férias</a></li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                @endif
                @if (in_array(1, $perfis ?? []))
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="1" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false" style="color:#ffffff;">Administrar
                                Sistema</a>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkdropdownMenuLink">

                                <li><a href="/gerenciar-usuario" class="dropdown-item">Gerenciar Usuários</a>
                                </li>


                                <li><a href="/gerenciar-setor-usuario" class="dropdown-item">Gerenciar Setor
                                        Usuários</a>
                                </li>


                                <li><a href="/gerenciar-perfis" class="dropdown-item">Gerenciar Perfis </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endif

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="4" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false" style="color: #ffffff">Logout</a>
                        <ul class="dropdown-menu " aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="/usuario/alterar-senha"><i
                                        class="mdi mdi-lock-open-outline font-size-17 text-muted align-middle mr-1"></i>Alterar
                                    Senha</a></li>
                            <li><a class="dropdown-item" href="javascript:void();"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                        class="mdi mdi-power font-size-17 text-muted align-middle mr-1 text-danger"></i>
                                    {{ __('Sair') }}</a></li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </li>
                </ul>
                </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
