{{-- resources/views/components/table.blade.php --}}

@props(['headers' => []])

<div class="overflow-hidden bg-white shadow-sm rounded-lg border border-gray-200 max-w-6xl mx-auto">
    <div class="overflow-x-auto">
        <!-- <table class="min-w-full divide-y divide-gray-200"> -->
        <table class="min-w-full table-fixed w-full divide-y divide-gray-200">
            @if(count($headers) > 0)
                <thead class="bg-gray-50">
                    <tr>
                        @foreach($headers as $header)
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>