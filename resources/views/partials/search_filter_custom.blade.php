{{-- Search and Filter Component --}}
<div class="flex items-center justify-between mb-6 bg-gray-50 p-4 rounded-lg">
    {{-- Search Input --}}
    <div class="flex items-center space-x-4">
        <label for="search" class="text-sm font-medium text-gray-700">Cari:</label>
        <input 
            type="text" 
            id="search" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="{{ $placeholder ?? 'Cari berdasarkan nama, kode, kategori, atau pengguna...' }}" 
            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
    </div>
    
    {{-- Items Per Page --}}
    <div class="flex items-center space-x-2">
        <label for="per_page" class="text-sm font-medium text-gray-700">Tampilkan:</label>
        <select 
            id="per_page" 
            name="per_page" 
            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
            <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
        </select>
        <span class="text-sm text-gray-700">per halaman</span>
    </div>
</div>

{{-- JavaScript untuk auto-submit form --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const perPageSelect = document.getElementById('per_page');
    let searchTimeout;
    
    // Auto-submit search setelah 500ms tanpa ketikan
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            submitForm();
        }, 500);
    });
    
    // Auto-submit per page change
    perPageSelect.addEventListener('change', function() {
        submitForm();
    });
    
    function submitForm() {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = window.location.pathname;
        
        // Preserve existing query parameters
        const urlParams = new URLSearchParams(window.location.search);
        
        // Add search parameter
        if (searchInput.value) {
            urlParams.set('search', searchInput.value);
        } else {
            urlParams.delete('search');
        }
        
        // Add per_page parameter
        urlParams.set('per_page', perPageSelect.value);
        
        // Add all parameters to form
        for (const [key, value] of urlParams) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
        
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
