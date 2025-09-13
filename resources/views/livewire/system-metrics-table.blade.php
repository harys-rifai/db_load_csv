<div class="p-4 space-y-4 w-full">
    {{-- Controls Section --}}
    <div class="flex items-center justify-between">
        {{-- Kiri --}}
        <div class="flex items-center gap-4">
            {{-- Dropdown Jumlah Data --}}
            <div>
                <label for="perPage" class="text-sm font-medium text-gray-700">Show:</label>
                <select wire:model="perPage" id="perPage" class="ml-2 px-4 py-1 border rounded text-sm">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            {{-- Checkbox Filter Hari Ini --}}
            <div>
                <label class="inline-flex items-right text-sm font-medium text-gray-700">
                    <input type="checkbox" wire:model="filterToday" class="form-checkbox text-blue-600">
                    <span class="ml-2">Today</span>
                </label>
            </div>
        </div>
        {{-- Tengah (Judul Tabel) --}}
        <div class="flex-1 text-center">
            <button wire:click="$toggle('showAll')" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                {{ $showAll ? 'Show Limit' : 'Show All' }}
            </button>
        </div>
    </div>
    {{-- Table Section --}}
    <div class="w-full overflow-x-auto bg-white border border-gray-300 rounded-md shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    @php
                        $headers = [
                            'timestamp' => 'Sort by → Date in Time',
                            'hostname' => 'Hostname',
                            'environment' => 'DB Name',
                            'cpu_usage' => 'CPU',
                            'memory_usage' => 'Memory',
                            'disk_usage' => 'Disk',
                            'network_usage' => 'Network',
                            'pgver' => 'PGV',  // Added here
                            'status' => 'Status', //extra1
                            'extra1' => 'Long Query', //extra1
                            'extra2' => 'Locking', //extra1
                        ];
                    @endphp
                    @foreach ($headers as $field => $label)
                        <th class="px-3 py-2 cursor-pointer whitespace-nowrap" wire:click="sortBy('{{ $field }}')">
                            {{ $label }}
                            @if ($sortField === $field)
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($showAll ? $metrics : $metrics->items() as $metric)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2 whitespace-nowrap  hover:text-blue-500 hover:font-bold">
                        {{ \Carbon\Carbon::parse($metric->timestamp)->format('Y-m-d H:i:s') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap  hover:text-blue-500 hover:font-bold">
                        <span class="w-3 h-3 rounded-full inline-block" style="border: 2px solid {{ $metric->env_color }}; background-color: transparent"></span>
                        {{ $metric->hostname }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full inline-block" style="background-color: {{ $metric->env_color }}"></span>
                                <span class="text-xs font-medium text-gray-700">{{ $metric->environment }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap {{ floatval($metric->cpu_usage) > 75 ? 'bg-red-100 text-red-600 font-semibold' : 'bg-purple-50 text-purple-500'  }}">
                            {{ $metric->cpu_usage }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap {{ $metric->memory_percent > 75 ? 'bg-yellow-100 text-yellow-600 font-semibold' : 'text-blue-500'  }}">
                            {{ $metric->memory_percent }}%
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap {{ floatval($metric->disk_usage) >= 74 ? 'bg-red-100 text-red-600 font-semibold' : 'bg-blue-50 text-blue-500' }}">
                            {{ $metric->disk_usage }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap {{ floatval($metric->network_usage) >= 74 ? 'bg-red-100 text-red-600 font-semibold' : 'bg-purple-50 text-purple-500' }}">
                            {{ $metric->network_usage }}
                        </td>
                         <td class="px-4 py-2 whitespace-nowrap {{ floatval($metric->pgver) >= 74 ? 'bg-red-100 text-red-600 font-semibold' : 'bg-blue-50 text-blue-500' }}">
                             {{ $metric->pgver }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap ">
                        @php
                            switch ($metric->status) {
                                case 'accepting':
                                    $icon = '✅'; // Accepting
                                    break;
                                case 'fail':
                                    $icon = '❌'; // Failed
                                    break;
                                case 'panic':
                                    $icon = '🚨'; // Panic
                                    break;
                                case 'down':
                                    $icon = '🔻'; // Down
                                    break;
                                default:
                                    $icon = '⚠️'; // Unknown or warning
                            }
                        @endphp
                        {{ $icon }} 
                        </td>
                        <td class="px-2 py-1 text-sm whitespace-nowrap {{ floatval($metric->extra1) >= 1 ? 'bg-red-100 text-red-500 font-semibold' : 'bg-blue-50 text-blue-500' }}">
                            {{ $metric->extra1 }}
                        </td>
                        <td class="px-2 py-1 text-sm whitespace-nowrap {{ floatval($metric->extra2) >= 1 ? 'bg-red-100 text-red-500 font-semibold' : 'bg-blue-50 text-blue-500' }}">
                            {{ $metric->extra2 }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-2 text-center text-gray-500">Tidak ada data ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- Pagination Section --}}
    @unless($showAll)
        <div class="mt-4 px-4 py-3 border-t bg-gray-50 flex flex-col items-center justify-center gap-4 text-sm">
            <div class="text-gray-600 text-center">
                Halaman <strong>{{ $metrics->currentPage() }}</strong> dari <strong>{{ $metrics->lastPage() }}</strong>
                — Menampilkan <strong>{{ $metrics->total() }}</strong> baris data
            </div>

            <div class="flex items-center justify-center space-x-2 overflow-x-auto">
                {{-- Previous Button --}}
                @if ($metrics->onFirstPage())
                    <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">←</span>
                @else
                    <button wire:click="previousPage" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-blue-50 text-blue-600 transition">←</button>
                @endif

                {{-- Page Numbers --}}
                @php
                    $start = max(1, $metrics->currentPage() - 2);
                    $end = min($metrics->lastPage(), $metrics->currentPage() + 2);
                @endphp
                @if ($start > 1)
                    <button wire:click="gotoPage(1)" class="px-3 py-1 border rounded bg-white text-blue-600 hover:bg-blue-50">1</button>
                    @if ($start > 2)
                        <span class="px-2">...</span>
                    @endif
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    <button wire:click="gotoPage({{ $i }})"
                        class="px-3 py-1 border rounded {{ $metrics->currentPage() === $i ? 'bg-blue-500 text-white' : 'bg-white text-blue-600 hover:bg-blue-50' }}">
                        {{ $i }}
                    </button>
                @endfor

                @if ($end < $metrics->lastPage())
                    @if ($end < $metrics->lastPage() - 1)
                        <span class="px-2">...</span>
                    @endif
                    <button wire:click="gotoPage({{ $metrics->lastPage() }})" class="px-3 py-1 border rounded bg-white text-blue-600 hover:bg-blue-50">{{ $metrics->lastPage() }}</button>
                @endif

                {{-- Next Button --}}
                @if ($metrics->hasMorePages())
                    <button wire:click="nextPage" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-blue-50 text-blue-600 transition">→</button>
                @else
                    <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">→</span>
                @endif
            </div>
        </div>
    @endunless
            <div style="border: 0px solid #ccc; padding: 10px; max-width: 300px; font-size: 0.85em;">
             Legend: 
            <ul style="list-style: none; padding-left: 0; margin: 5px 0;">
                <li><span style="font-size: 0.9em;">✅</span> Accepting</li>
                <li><span style="font-size: 0.9em;">❌</span> Fail</li>
                <li><span style="font-size: 0.9em;">🚨</span> Panic</li>
                <li><span style="font-size: 0.9em;">🔻</span> Down</li>
                <li><span style="font-size: 0.9em;">⚠️</span> Other / Unknown</li>
            </ul>
            </div>
</div>