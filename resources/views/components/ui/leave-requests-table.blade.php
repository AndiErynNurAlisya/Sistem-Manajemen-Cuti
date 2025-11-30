{{-- resources/views/components/leave-requests-table.blade.php --}}
@props([
    'leaveRequests',
    'showRoute' => 'employee.leave-requests.show',  // Default route
    'cancelRoute' => 'employee.leave-requests.index', // Default cancel route
    'showCancelButton' => true  // Show cancel button by default
])

<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Jenis Cuti
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Periode
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Durasi Cuti
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Tanggal Ajuan
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($leaveRequests as $leave)
                <tr class="hover:bg-gray-50 transition">
                    {{-- Jenis Cuti --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-ui.leave-type-badge :type="$leave->leave_type" />
                    </td>

                    {{-- Periode --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ formatDate($leave->start_date) }}
                        </div>
                        <div class="text-sm text-gray-500">
                            s/d {{ formatDate($leave->end_date) }}
                        </div>
                    </td>

                    {{-- Durasi --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <span class="font-medium">{{ $leave->total_days }}</span> hari
                    </td>

                    {{-- Tanggal Ajuan --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $leave->request_date->format('d M Y') }}
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-ui.leave-status-badge :status="$leave->status" />
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <x-breeze.button-action
                            :href="route($showRoute, $leave)"
                            color="army"
                            class="mr-3"
                        >
                            Detail
                        </x-breeze.button-action>

                        {{-- Tombol Batalkan --}}
                        @if($showCancelButton && $leave->canBeCancelled())
                            <x-breeze.button-action
                                href="javascript:void(0)"
                                color="danger"
                                onclick="openCancelModal({{ $leave->id }})"
                            >
                                Batalkan
                            </x-breeze.button-action>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>