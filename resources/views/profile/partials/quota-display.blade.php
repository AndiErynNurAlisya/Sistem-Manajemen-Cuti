@if($quota && in_array(auth()->user()->role->value, ['employee', 'leader']))
    <section class="flex items-center justify-center">
        <header>
            <h2 class="text-lg font-medium" style="color: #334124;">
                {{ __('Informasi Kuota Cuti') }}
            </h2>

            <p class="mt-1 mb-8 text-sm text-gray-600">
                {{ __('Status kuota cuti Anda untuk tahun ini.') }}
            </p>
        </header>

        <div class="mt-16 p-6 bg-white border rounded-lg shadow-sm max-w-md mx-auto" style="border-color: #b5b89b;">
            @php
                $remaining = is_array($quota) ? ($quota['remaining'] ?? 0) : ($quota->remaining_quota ?? 0);
                $total = is_array($quota) ? ($quota['total'] ?? 12) : ($quota->total_quota ?? 12);
                $used = is_array($quota) ? ($quota['used'] ?? 0) : ($quota->used_quota ?? 0);
                $percentage = $total > 0 ? round(($remaining / $total) * 100) : 0;
            @endphp

            <div class="grid grid-cols-3 gap-4 mb-6 ">
                <div class="text-center p-4 rounded-lg" style="background-color: #f9fafb;">
                    <div class="text-sm text-gray-600 mb-1">Total Kuota</div>
                    <div class="text-3xl font-bold" style="color: #566534;">{{ $total }}</div>
                    <div class="text-xs text-gray-500 mt-1">hari</div>
                </div>

                <div class="text-center p-4 rounded-lg" style="background-color: #f9fafb;">
                    <div class="text-sm text-gray-600 mb-1">Terpakai</div>
                    <div class="text-3xl font-bold text-red-600">{{ $used }}</div>
                    <div class="text-xs text-gray-500 mt-1">hari</div>
                </div>

                <div class="text-center p-4 rounded-lg" style="background-color: #f9fafb;">
                    <div class="text-sm text-gray-600 mb-1">Sisa Kuota</div>
                    <div class="text-3xl font-bold text-green-600">{{ $remaining }}</div>
                    <div class="text-xs text-gray-500 mt-1">hari</div>
                </div>
            </div>
        </div>
    </section>
@endif