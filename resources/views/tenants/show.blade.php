@extends('layouts.app')

@section('title', 'Ver Empresa')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('tenants.index') }}" class="text-gray-500 hover:text-gray-700">Empresas</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">{{ $tenant->name }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tenant->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Estado: 
                <span class="px-2 py-1 rounded text-xs font-semibold {{ $tenant->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                    {{ $tenant->is_active ? 'Activa' : 'Inactiva' }}
                </span>
            </p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('tenants.edit', $tenant) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                Editar
            </a>
            <a href="{{ route('tenants.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Información General</h2>
            <div class="space-y-3">
                @if($tenant->razon_social)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Razón Social</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $tenant->razon_social }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $tenant->email }}</p>
                </div>
                @if($tenant->phone)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Teléfono</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $tenant->phone }}</p>
                </div>
                @endif
                @if($tenant->rfc)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">RFC</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $tenant->rfc }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Moneda</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $tenant->currency }}</p>
                </div>
                @if($tenant->address)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Dirección</p>
                    <p class="text-gray-900 dark:text-white">{{ $tenant->address }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Estadísticas</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Usuarios</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tenant->users->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Cuentas Contables</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tenant->chartAccounts->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Slug</p>
                    <p class="text-sm font-mono text-gray-900 dark:text-white">{{ $tenant->slug }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($tenant->users->count() > 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Usuarios</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($tenant->users as $user)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection




