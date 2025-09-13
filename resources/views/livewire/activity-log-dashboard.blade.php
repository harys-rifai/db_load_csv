<div class="w-full px-6 py-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">📝 Activity Logs</h2>

    <div class="w-full bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="w-full table-auto divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User Agent</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $log->causer?->name ?? 'System' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $log->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $log->properties['ip'] ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($log->properties['user_agent'] ?? '-', 40) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div>{{ $log->created_at->format('Y-m-d H:i:s') }}</div>
                            <div class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>
