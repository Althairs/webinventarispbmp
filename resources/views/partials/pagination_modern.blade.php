{{-- Modern Pagination Component --}}
@if($data->hasPages())
<div class="flex items-center justify-between mt-8 mb-8 py-4">
    {{-- Info Text --}}
    <div class="text-sm text-gray-600">
        Menampilkan {{ $data->firstItem() }} sampai {{ $data->lastItem() }} dari {{ $data->total() }} hasil
    </div>
    
    {{-- Pagination Controls --}}
    <div class="flex items-center space-x-1">
        {{-- Previous Button --}}
        @if($data->onFirstPage())
            <button disabled class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-full cursor-not-allowed flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Sebelumnya
            </button>
        @else
            <a href="{{ $data->previousPageUrl() }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-full hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 flex items-center transition-colors duration-200">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Sebelumnya
            </a>
        @endif

        {{-- Page Numbers --}}
        <div class="flex items-center space-x-1">
            @php
                $currentPage = $data->currentPage();
                $lastPage = $data->lastPage();
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
                
                // Adjust range if we're near the beginning or end
                if ($endPage - $startPage < 4) {
                    if ($startPage == 1) {
                        $endPage = min($lastPage, $startPage + 4);
                    } else {
                        $startPage = max(1, $endPage - 4);
                    }
                }
            @endphp
            
            @for($page = $startPage; $page <= $endPage; $page++)
                @if($page == $currentPage)
                    <span class="px-3 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg shadow-sm">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $data->url($page) }}" class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors duration-200">
                        {{ $page }}
                    </a>
                @endif
            @endfor
        </div>

        {{-- Next Button --}}
        @if($data->hasMorePages())
            <a href="{{ $data->nextPageUrl() }}" class="px-4 py-2 text-sm font-medium text-purple-600 bg-white border border-purple-200 rounded-full hover:bg-purple-50 hover:border-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500 flex items-center transition-colors duration-200">
                Selanjutnya
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        @else
            <button disabled class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-full cursor-not-allowed flex items-center">
                Selanjutnya
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        @endif
    </div>
</div>
@endif
