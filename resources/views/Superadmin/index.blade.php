@extends('layouts.layout')
@section('title', 'Super Admin | dashboard')
@section('dashboard', 'active')
    

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Users</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['totalUsers'] }}</p>
                <p class="text-sm text-green-600">{{ $stats['activeUsers'] }} aktif</p>
            </div>
            <i class="fas fa-users text-purple-500 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Admin Users</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['adminUsers'] }}</p>
            </div>
            <i class="fas fa-user-shield text-blue-500 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Regular Users</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['regularUsers'] }}</p>
            </div>
            <i class="fas fa-user text-green-500 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">System Status</p>
                <p class="text-lg font-bold text-green-600">Online</p>
                <p class="text-sm text-gray-500">All systems operational</p>
            </div>
            <i class="fas fa-server text-green-500 text-2xl"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-3">
    <div class="bg-white rounded-lg card-shadow p-6">
        <h3 class="text-lg font-bold mb-4">User Distribution</h3>
        <canvas id="userDistributionChart" width="400" height="200"></canvas>
    </div>
    <div class="bg-white rounded-lg card-shadow p-6">
        <h3 class="text-lg font-bold mb-4">System Information</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                <span class="text-gray-700 font-medium">Server Status</span>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                    <i class="fas fa-circle text-green-500 mr-1"></i>Online
                </span>
            </div>
            <div class="flex justify-between items-center p-3 bg-blue-50 rounded">
                <span class="text-gray-700 font-medium">Database</span>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    <i class="fas fa-circle text-blue-500 mr-1"></i>Connected
                </span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <span class="text-gray-700 font-medium">Last Backup</span>
                <span class="text-gray-800 font-medium">2024-02-01 02:00</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <span class="text-gray-700 font-medium">System Version</span>
                <span class="text-gray-800 font-medium">v2.1.0</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg card-shadow p-6">
    <h3 class="text-lg font-bold mb-4">Recent User Activities</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">User</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Timestamp</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($systemLogs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <i class="fas fa-user-circle text-gray-400 mr-2"></i>
                                <span class="font-medium text-gray-900">{{ $log->user }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                @if (Str::contains($log->action, 'Login')) bg-blue-100 text-blue-800
                                @elseif (Str::contains($log->action, 'Add')) bg-green-100 text-green-800
                                @elseif (Str::contains($log->action, 'Delete')) bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $log->timestamp }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Success
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Skrip untuk chart --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('userDistributionChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Super Admin', 'Admin Users', 'Regular Users'],
                datasets: [{
                    data: [
                        {{ $userCounts['super_admin'] }},
                        {{ $userCounts['admin'] }},
                        {{ $userCounts['user'] }}
                    ],
                    backgroundColor: ['#8b5cf6', '#3b82f6', '#10b981'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '60%'
            }
        });
    });
</script>
@endpush
@endsection
