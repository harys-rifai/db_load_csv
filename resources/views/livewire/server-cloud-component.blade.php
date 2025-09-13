<div class="p-6 bg-white rounded shadow" wire:init="loadServers">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Server Cloud List</h1>

    {{-- Search & Filter --}}
    <div class="mb-4 flex flex-col md:flex-row gap-4 items-center">
        <input
            type="text"
            wire:model="search"
            placeholder="Search by name, appref, or IP..."
            class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-300"
        />
        <button wire:click="applyFilter" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Search
        </button>
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading class="text-blue-500 text-sm mb-2">Loading data...</div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded text-xs">
            <thead class="text-xs bg-gray-100">
                <tr>
                    @foreach ([
                        
                        'appref' => 'AppRef',
                        'ip' => 'IP Address',
                        'environment' => 'Env',
                        'description' => 'Description',
                        'pic' => 'PIC',
                        'tribe' => 'Tribe',
                        'version' => 'DB Vers.',
                        'database_name' => 'DB Name',
                        'processor' => 'Processor',
                        'memory' => 'Memory',
                        'storage' => 'Storage',
                        'encryption' => 'Encryption',
                        'pii' => 'PII',
                        'remark' => 'Remark' 
                    ] as $field => $label)
                        <th class="px-4 py-2 text-left font-semibold text-gray-700 cursor-pointer {{ $sortField === $field ? 'bg-blue-50' : '' }}"
                            wire:click="sortBy('{{ $field }}')">
                            {{ $label }}
                            @if ($sortField === $field)
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>

            @php use Illuminate\Support\Str; @endphp

            <tbody wire:loading.class="opacity-50" wire:loading.remove>
                @forelse ($servers as $server)
                    <tr class="text-xs hover:bg-blue-50 transition">
                        
                        <td> 
                        {{ $server->appref }}</td>
                        <td>{{ $server->ip }}</td>

                        {{-- Environment Coloring <span class="w-3 h-3 rounded-full inline-block" style="background-color:blue"></span> --}}
                        <td class="px-4 py-2 whitespace-nowrap">
                            @if($server->environment === 'PROD') <span class="w-3 h-3 rounded-full inline-block" style="border: 2px solid red; background-color: transparent"></span> 
                            @elseif($server->environment === 'UAT') <span class="w-3 h-3 rounded-full inline-block" style="border: 2px solid green; background-color: transparent"></span>
                            @else <span class="w-3 h-3 rounded-full inline-block" style="border: 2px solid orange; background-color: transparent"></span>
                            @endif
                            {{ $server->environment }}
                        </td>

                        <td class="text-xs whitespace-nowrap bg-purple-50 text-purple-700">{{ $server->description }}</td>

                        {{-- PIC and Tribe Coloring --}}
                        <td class="text-xs whitespace-nowrap text-purple-700">{{ $server->pic }}</td>
                        <td class="text-xs bg-indigo-50 text-indigo-700">{{ $server->tribe }}</td>

                        {{-- Trimmed Version --}}
                        <td class="text-xs whitespace-nowrap text-purple-700">{{ Str::limit($server->version, 16) }}</td>

                        <td class="text-xs whitespace-nowrap bg-purple-50 text-purple-700">{{ $server->database_name }}</td>

                        {{-- Processor Coloring --}}
                        <td class="px-4 py-2 whitespace-nowrap {{ floatval($server->processor) >= 90 ? 'bg-orange-100 text-orange-700 font-semibold' : 'bg-blue-50 text-gray-600' }}">
                            {{ $server->processor }}
                        </td>

                        {{-- Memory Coloring --}}
                        <td class="px-4 py-2 whitespace-nowrap {{ floatval($server->memory) >= 74 ? 'bg-red-100 text-red-600 font-semibold' : 'bg-green-50 text-green-600' }}">
                            {{ $server->memory }}
                        </td>

                        {{-- Storage Coloring --}}
                        <td class="px-4 py-2 whitespace-nowrap {{ floatval($server->storage) >= 80 ? 'bg-yellow-100 text-yellow-700 font-semibold' : 'bg-blue-50 text-blue-600' }}">
                            {{ $server->storage }}
                        </td>

                       {{-- Encryption & PII --}}
                        <td class="{{ $server->encryption ? 'bg-green-100 text-green-700 font-semibold' : 'bg-gray-50 text-gray-600' }}">
                            {{ $server->encryption ? '🔒 Yes' : '❌' }}
                        </td>
                        <td class="{{ $server->pii ? 'bg-green-100 text-red-700 font-semibold' : 'bg-gray-50 text-gray-600' }}">
                            {{ $server->pii ? '🔒 Yes' : '❌' }}
                        </td>

                        {{-- remark --}}
                        <td class="text-xs text-white hover:text-red-500 hover:bg-yellow-100 transition relative group">
                            {{ $server->remark }}
                        </td> 


                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="px-4 py-4 text-center text-gray-500 text-xs">No results found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($servers instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-4">
            {{ $servers->links() }}
        </div>
    @endif

    <p class="text-sm text-gray-500">Ready to load: {{ $readyToLoad ? 'Yes' : 'No' }}</p>
    <p class="text-sm text-gray-500">Search: {{ $search }}</p>
</div>
