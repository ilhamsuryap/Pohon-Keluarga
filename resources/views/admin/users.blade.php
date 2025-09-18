@extends('layouts.admin', ['title' => 'Kelola User - Admin Dashboard'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-bold text-gray-900">Kelola User</h1>
            <p class="mt-1 text-sm text-gray-600">Setujui pendaftaran dan kelola status user</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar User</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Kelola status persetujuan dan pembayaran user</p>
        </div>
        
        @if($users->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ substr($user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="ml-2 flex space-x-1">
                                            @if(!$user->is_approved)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Menunggu Persetujuan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Disetujui
                                                </span>
                                            @endif
                                            
                                            @if($user->payment_status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Menunggu Verifikasi
                                                </span>
                                            @elseif($user->payment_status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Pembayaran Disetujui
                                                </span>
                                            @elseif($user->payment_status === 'rejected')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Pembayaran Ditolak
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $user->email }} • {{ $user->phone }}
                                    </div>
                                    @if($user->payment_amount)
                                        <div class="text-sm text-gray-500">
                                            Jumlah Pembayaran: Rp {{ number_format($user->payment_amount, 0, ',', '.') }}
                                            @if($user->payment_code)
                                                (Kode: {{ $user->payment_code }})
                                            @endif
                                        </div>
                                    @endif
                                    @if($user->hasUploadedPaymentProof())
                                        <div class="text-sm text-blue-600">
                                            <a href="#" onclick="showPaymentProof('{{ $user->getPaymentProofUrl() }}', '{{ $user->name }}')" class="hover:underline">
                                                📎 Lihat Bukti Pembayaran
                                            </a>
                                            <span class="text-gray-500 ml-2">
                                                ({{ $user->payment_proof_uploaded_at->format('d/m/Y H:i') }})
                                            </span>
                                        </div>
                                    @endif
                                    <div class="text-xs text-gray-400">
                                        Terdaftar: {{ $user->created_at->format('d M Y H:i') }}
                                        @if($user->families->count() > 0)
                                            • Keluarga: {{ $user->families->first()->family_name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                @if(!$user->is_approved)
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                onclick="return confirm('Apakah Anda yakin ingin menyetujui user ini?')">
                                            Setujui
                                        </button>
                                    </form>
                                @endif
                                
                                @if($user->payment_status === 'pending' && $user->hasUploadedPaymentProof())
                                    <button onclick="showApprovalModal({{ $user->id }}, '{{ $user->name }}')"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Setujui Pembayaran
                                    </button>
                                    <button onclick="showRejectionModal({{ $user->id }}, '{{ $user->name }}')"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Tolak Pembayaran
                                    </button>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada user</h3>
                <p class="mt-1 text-sm text-gray-500">Belum ada user yang terdaftar dalam sistem.</p>
            </div>
        @endif
    </div>
</div>

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="paymentProofTitle">Bukti Pembayaran</h3>
                <button onclick="closePaymentProofModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="text-center">
                <img id="paymentProofImage" src="" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg">
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Setujui Pembayaran</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="approvalMessage">
                    Apakah Anda yakin ingin menyetujui pembayaran untuk user ini?
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="approvalForm" method="POST">
                    @csrf
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeApprovalModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                            Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2 text-center">Tolak Pembayaran</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 text-center" id="rejectionMessage">
                    Berikan alasan penolakan pembayaran:
                </p>
                <form id="rejectionForm" method="POST" class="mt-4">
                    @csrf
                    <textarea name="rejection_reason" 
                              class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500" 
                              rows="3" 
                              placeholder="Alasan penolakan (opsional)"></textarea>
                    <div class="flex space-x-3 mt-4">
                        <button type="button" onclick="closeRejectionModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showPaymentProof(imageUrl, userName) {
    document.getElementById('paymentProofImage').src = imageUrl;
    document.getElementById('paymentProofTitle').textContent = 'Bukti Pembayaran - ' + userName;
    document.getElementById('paymentProofModal').classList.remove('hidden');
}

function closePaymentProofModal() {
    document.getElementById('paymentProofModal').classList.add('hidden');
}

function showApprovalModal(userId, userName) {
    document.getElementById('approvalMessage').textContent = 
        'Apakah Anda yakin ingin menyetujui pembayaran untuk ' + userName + '?';
    document.getElementById('approvalForm').action = '/admin/users/' + userId + '/approve';
    document.getElementById('approvalModal').classList.remove('hidden');
}

function closeApprovalModal() {
    document.getElementById('approvalModal').classList.add('hidden');
}

function showRejectionModal(userId, userName) {
    document.getElementById('rejectionMessage').textContent = 
        'Berikan alasan penolakan pembayaran untuk ' + userName + ':';
    document.getElementById('rejectionForm').action = '/admin/users/' + userId + '/reject-payment';
    document.getElementById('rejectionModal').classList.remove('hidden');
}

function closeRejectionModal() {
    document.getElementById('rejectionModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('paymentProofModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentProofModal();
    }
});

document.getElementById('approvalModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApprovalModal();
    }
});

document.getElementById('rejectionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectionModal();
    }
});
</script>
@endsection