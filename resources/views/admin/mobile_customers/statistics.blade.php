@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">إحصائيات العملاء عبر الجوال</h1>
                        <p class="text-gray-600 mt-1">عرض إحصائيات مفصلة عن نشاط العملاء عبر التطبيق الجوال</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.mobile-customers.index') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الدخول (30 يوم)</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dailyLogins->sum('count') }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-mobile-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">دخول اليوم</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dailyLogins->where('date', today()->format('Y-m-d'))->first()?->count ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-calendar-day text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">دخول هذا الأسبوع</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dailyLogins->whereBetween('date', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')])->sum('count') }}</p>
                    </div>
                    <div class="bg-info-100 p-3 rounded-full">
                        <i class="fas fa-calendar-week text-info-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">متوسط الدخول اليومي</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dailyLogins->avg('count') ? round($dailyLogins->avg('count'), 1) : 0 }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Daily Login Chart -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-chart-line text-blue-600"></i>
                            الدخول اليومي (آخر 30 يوم)
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <canvas id="dailyLoginChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Device Type Distribution -->
            <div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-pie-chart text-green-600"></i>
                            توزيع أنواع الأجهزة
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <canvas id="deviceTypeChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Hourly Statistics -->
            <div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-clock text-purple-600"></i>
                            الدخول حسب الساعة (آخر أسبوع)
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <canvas id="hourlyChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Active Users -->
            <div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-trophy text-orange-600"></i>
                            أكثر العملاء نشاطاً
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">آخر دخول</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الجهاز</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $topUsers = \App\Models\Customer::whereNotNull('last_mobile_login')
                                            ->orderBy('last_mobile_login', 'desc')
                                            ->take(5)
                                            ->get();
                                    @endphp
                                    @forelse($topUsers as $user)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-sm">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="mr-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $user->phone }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <small class="text-sm text-gray-600">
                                                @if($user->last_mobile_login)
                                                    {{ \Carbon\Carbon::parse($user->last_mobile_login)->diffForHumans() }}
                                                @else
                                                    غير محدد
                                                @endif
                                            </small>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($user->device_type == 'android')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Android
                                                </span>
                                            @elseif($user->device_type == 'ios')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info-100 text-info-800">
                                                    iOS
                                                </span>
                                            @elseif($user->device_type == 'web')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Web
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    غير محدد
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-chart-bar text-gray-400 text-2xl mb-2"></i>
                                                <p class="text-gray-500">لا يوجد بيانات</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Login Chart
    const dailyCtx = document.getElementById('dailyLoginChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyLogins->pluck('date')) !!},
            datasets: [{
                label: 'عدد الدخول',
                data: {!! json_encode($dailyLogins->pluck('count')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    });

    // Device Type Chart
    const deviceCtx = document.getElementById('deviceTypeChart').getContext('2d');
    new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deviceStats->pluck('device_type')) !!},
            datasets: [{
                data: {!! json_encode($deviceStats->pluck('count')) !!},
                backgroundColor: [
                    'rgb(34, 197, 94)', // green
                    'rgb(59, 130, 246)', // blue
                    'rgb(245, 158, 11)', // yellow
                    'rgb(107, 114, 128)' // gray
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Hourly Chart
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($hourlyStats->pluck('hour')->map(function($hour) { return $hour . ':00'; })) !!},
            datasets: [{
                label: 'عدد الدخول',
                data: {!! json_encode($hourlyStats->pluck('count')) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    });
});
</script>
@endsection
