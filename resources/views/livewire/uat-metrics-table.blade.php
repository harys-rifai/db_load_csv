<div class="p-4 space-y-6 w-full text-xs">
    {{-- Filter & Search --}}
    <div class="space-y-3">
        <input type="text" wire:model="search" placeholder="Search Hostname / Database..." class="p-2 border rounded w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />

        <div class="flex flex-wrap gap-3">
            <label class="flex items-center space-x-2">
                <input type="checkbox" wire:model="filterToday" class="form-checkbox text-blue-600" />
                <span class="text-gray-700">Tampilkan data hari ini</span>
            </label>

            <label class="flex items-center space-x-2">
                <span class="text-gray-700">Per Page:</span>
                <select wire:model="perPage" class="p-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </label>

            <button wire:click="$set('showAll', !showAll)" class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600">
                {{ $showAll ? 'Gunakan Pagination' : 'Tampilkan Semua' }}
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white border border-gray-300 rounded-md shadow-sm">
        <table class="table-auto w-full border-collapse">
            <thead class="bg-gray-100 text-left">
                <tr>
                    @php
                        $headers = ['Timestamp', 'Hostname', 'IP', 'Database', 'CPU', 'Memory', 'Disk Vol', 'Disk Data', 'Status', 'LongQuery', 'Locking', 'Version', 'Flag', 'State'];
                    @endphp
                    @foreach ($headers as $header)
                        <th class="border px-3 py-2 font-semibold text-gray-700 cursor-pointer" wire:click="sortBy('{{ $header }}')">
                            {{ $header }}
                            @if ($sortField === $header)
                                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($metrics as $metric)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="border px-3 py-2">{{ $metric->Timestamp }}</td>
                        <td class="border px-3 py-2">
                            <span class="w-3 h-3 rounded-full inline-block" style="border: 2px solid {{ $metric->db_color }}; background-color: transparent"></span>
                            {{ $metric->Hostname }}
                        </td>
                        <td class="border px-3 py-2">{{ $metric->IP_Address }}</td>
                        <td class="border px-3 py-2">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full inline-block" style="background-color: {{ $metric->db_color }}"></span>
                                <span class="text-xs font-medium text-gray-700">{{ $metric->Database }}</span>
                            </div>
                        </td>

                        <td class="px-4 py-2 whitespace-nowrap {{ $metric->cpu_percent > 80 ? 'bg-red-100 text-red-600 font-semibold' : 'bg-purple-50 text-purple-500' }}">
                            {{ $metric->CPU }}
                        </td>

                        <td class="px-4 py-2 whitespace-nowrap {{ $metric->memory_percent > 80 ? 'bg-red-100 text-red-600 font-semibold' : 'bg-purple-50 text-purple-500' }}">
                            {{ $metric->Memory }}
                        </td>

                        <td class="border px-3 py-2 {{ $metric->DiskVolGroupAvg > 75 ? 'bg-red-50 text-red-500 font-semibold' : 'text-gray-700' }}">
                            {{ $metric->DiskVolGroupAvg }}
                        </td>

                        <td class="border px-3 py-2 {{ $metric->DiskDataAvg > 75 ? 'bg-red-50 text-red-500 font-semibold' : 'text-gray-700' }}">
                            {{ $metric->DiskDataAvg }}
                        </td>

                        <td class="border px-3 py-2">
                            @php
                                $statusIcons = [
                                    'accepting' => '✅',
                                    'fail' => '❌',
                                    'panic' => '🚨',
                                    'down' => '🔻',
                                ];
                                $statusIcon = $statusIcons[$metric->ServerStatus] ?? '⚠️';
                            @endphp
                            <span title="{{ $metric->ServerStatus }}">{{ $statusIcon }}</span>
                        </td>

                        <td class="border px-3 py-2 {{ $metric->LongQueryCount > 0 ? 'bg-orange-100 text-orange-600 font-semibold' : 'text-gray-700' }}">
                            {{ $metric->LongQueryCount }}
                        </td>

                        <td class="border px-3 py-2 {{ $metric->LockingCount > 0 ? 'bg-red-100 text-red-600 font-semibold' : 'text-gray-700' }}">
                            {{ $metric->LockingCount }}
                        </td>

                        <td class="border px-3 py-2">{{ $metric->PostgresVersion }}</td>

                        <td class="border px-3 py-2 {{ $metric->flag === 'Warning' ? 'bg-yellow-100 text-yellow-600 font-semibold' : 'text-gray-700' }}">
                            {{ $metric->flag }}
                        </td>

                        <td class="border px-3 py-2 {{ $metric->state === 'Critical' ? 'bg-red-200 text-red-700 font-bold' : 'text-gray-700' }}">
                            {{ $metric->state }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="text-center py-4 text-gray-500">Tidak ada data ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @unless($showAll)
        <div class="mt-4">
            {{ $metrics->links() }}
        </div>
    @endunless
</div>