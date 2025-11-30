{{-- resources/views/components/approval/employee-card.blade.php --}}
{{-- Reusable component untuk menampilkan info karyawan di approval page --}}

@props(['leaveRequest'])

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    {{-- Header with Gradient & Employee Profile --}}
    <div class="px-6 py-8 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #566534 0%, #334124 100%);">
        <div class="relative z-10">
            <div class="flex items-center space-x-6">
                {{-- Large Avatar --}}
                <div class="flex-shrink-0">
                    <img 
                        src="{{ getProfilePhotoUrl($leaveRequest->user) }}" 
                        alt="{{ $leaveRequest->user->full_name }}"
                        class="h-24 w-24 rounded-full object-cover ring-4 ring-white shadow-lg"
                    >
                </div>

                {{-- Employee Info --}}
                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-2">{{ $leaveRequest->user->full_name }}</h2>
                    <div class="flex flex-wrap items-center gap-4 text-sm opacity-90">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            {{ $leaveRequest->user->email }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                            </svg>
                           @if($leaveRequest->user->division)
                                {{ $leaveRequest->user->division->name }}
                            @else
                                <span class="text-red-600">⚠️ Belum Ada Divisi</span>
                            @endif
                        </span>
                        @if($leaveRequest->user->phone)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                {{ $leaveRequest->user->phone }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Quota Badge --}}
                <div class="flex-shrink-0 text-center bg-white/20 backdrop-blur-sm rounded-lg px-6 py-4">
                    <div class="text-xs uppercase tracking-wide mb-1 opacity-90">Sisa Kuota</div>
                    <div class="text-3xl font-bold">{{ $leaveRequest->user->leaveQuota->remaining_quota ?? 0 }}</div>
                    <div class="text-xs opacity-75">dari {{ $leaveRequest->user->leaveQuota->total_quota ?? 12 }} hari</div>
                </div>
            </div>
        </div>
        
        {{-- Decorative Pattern --}}
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 opacity-10">
            <svg viewBox="0 0 200 200" fill="currentColor">
                <path d="M45.8,-50.2C59.1,-39.7,69.5,-24.8,72.3,-8.7C75.1,7.4,70.3,24.7,59.9,37.9C49.5,51.1,33.5,60.2,16.7,64.8C-0.1,69.4,-17.7,69.5,-33.3,63.8C-48.9,58.1,-62.5,46.6,-69.8,31.8C-77.1,17,-78.1,-1.1,-73.3,-17.7C-68.5,-34.3,-57.9,-49.4,-43.8,-59.6C-29.7,-69.8,-14.8,-75.1,0.5,-75.7C15.8,-76.3,31.6,-72.2,45.8,-50.2Z"/>
            </svg>
        </div>
    </div>
</div>