<aside class="fixed top-0 left-0 h-screen w-72 bg-white dark:bg-gray-900 shadow-lg z-40 transition-transform duration-300 flex flex-col"
       x-data="{ openMenu: null }"
       :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

    <!-- Menu Section -->
    <nav class="px-4 py-6 space-y-4 flex-1 overflow-y-auto">
        @foreach ($menus as $index => $menu)
            <div>
                <button @click="openMenu === {{ $index }} ? openMenu = null : openMenu = {{ $index }}"
                        class="flex items-center w-full px-4 py-2 text-left text-gray-700 dark:text-gray-200 hover:text-blue-500 dark:hover:text-blue-400 rounded-lg transition-colors duration-200">
                    <x-dynamic-component :component="$menu->icon" class="w-5 h-5 mr-3" />
                    <span class="text-sm font-medium">{{ $menu->title }}</span>
                </button>

                @if ($menu->children->isNotEmpty())
                    <div x-show="openMenu === {{ $index }}" x-collapse x-transition
                         class="ml-6 mt-2 space-y-1">
                        @foreach ($menu->children as $child)
                            <a href="{{ route($child->route) }}"
                               class="flex items-center text-sm text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 transition-colors duration-200 py-1.5 pl-2 rounded-md">
                                <x-dynamic-component :component="$child->icon" class="w-4 h-4 mr-2" />
                                <span>{{ $child->title }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </nav>

    <!-- Footer Section -->
    <footer class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400">
        <div class="flex items-center justify-between">
            <span>&copy; {{ date('Y') }} DBA PLA - IND</span>
            <a href="#" class="hover:text-blue-500 dark:hover:text-blue-400 transition-colors duration-200"> </a>
        </div>
    </footer>
</aside>
