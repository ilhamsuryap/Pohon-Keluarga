@extends('layouts.admin', ['title' => 'Kelola User - Admin Dashboard'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl border border-white/20 animate-fade-in-up">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-3xl font-bold gradient-text">Kelola User</h1>
            <p class="mt-2 text-sm text-gray-600">Setujui pendaftaran dan kelola status user</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white/80 backdrop-blur-sm shadow-2xl overflow-hidden rounded-3xl border border-white/20 animate-fade-in-up">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-2xl font-bold text-gray-900">Daftar User</h3>
            <p class="mt-2 max-w-2xl text-sm text-gray-500">Kelola status persetujuan dan pembayaran user</p>
        </div>
        
        @if($users->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <li class="px-4 py-4 sm:px-6 hover:bg-white/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-300 to-blue-400 flex items-center justify-center shadow-lg">
                                        <span class="text-sm font-bold text-white">
                                            {{ substr($user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="ml-2 flex space-x-1">
                                            @if(!$user->is_approved)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-yellow-300 to-orange-400 text-white shadow-lg">
                                                    Menunggu Persetujuan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-300 to-emerald-400 text-white shadow-lg">
                                                    Disetujui
                                                </span>
                                            @endif
                                            
                                            @if($user->payment_status === 'pending')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-yellow-300 to-orange-400 text-white shadow-lg">
                                                    Menunggu Verifikasi
                                                </span>
                                            @elseif($user->payment_status === 'approved')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-300 to-emerald-400 text-white shadow-lg">
                                                    Pembayaran Disetujui
                                                </span>
                                            @elseif($user->payment_status === 'rejected')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-red-300 to-pink-400 text-white shadow-lg">
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
                                        <div class="text-sm mt-2">
                                            <a href="#" onclick="showPaymentProof('{{ $user->getPaymentProofUrl() }}', '{{ $user->name }}')" class="inline-flex items-center px-3 py-1 rounded-xl bg-gradient-to-r from-blue-300 to-indigo-400 text-white font-medium hover:from-blue-400 hover:to-indigo-500 transition-all shadow-lg transform hover:scale-105">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                                Lihat Bukti Pembayaran
                                                <span class="ml-2 text-xs opacity-90">
                                                    ({{ $user->payment_proof_uploaded_at->format('d/m/Y H:i') }})
                                                </span>
                                            </a>
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
                                @if((!$user->is_approved || ($user->payment_status === 'pending' && $user->hasUploadedPaymentProof())))
                                    <button onclick="showApprovalModal({{ $user->id }}, '{{ $user->name }}', {{ !$user->is_approved ? 'true' : 'false' }}, {{ ($user->payment_status === 'pending' && $user->hasUploadedPaymentProof()) ? 'true' : 'false' }})"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-xl text-white bg-gradient-to-r from-green-400 to-emerald-500 hover:from-green-500 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400 shadow-lg transition-all transform hover:scale-105">
                                        Setujui
                                    </button>
                                @endif
                                
                                @if($user->payment_status === 'pending' && $user->hasUploadedPaymentProof())
                                    <button onclick="showRejectionModal({{ $user->id }}, '{{ $user->name }}')"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-xl text-white bg-gradient-to-r from-red-400 to-pink-500 hover:from-red-500 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 shadow-lg transition-all transform hover:scale-105">
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
            <div class="text-center py-16">
                <div class="mx-auto w-20 h-20 bg-gradient-to-br from-purple-300 to-blue-400 rounded-full flex items-center justify-center mb-6 shadow-lg">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Belum ada user</h3>
                <p class="mt-2 text-sm text-gray-500">Belum ada user yang terdaftar dalam sistem.</p>
            </div>
        @endif
    </div>
</div>

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-3xl bg-white/95 backdrop-blur-md border-white/20">
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
<div id="approvalModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-2xl rounded-3xl bg-white/95 backdrop-blur-md border-white/20">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-green-300 to-emerald-400 shadow-lg">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-xl w-full shadow-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-green-400 to-emerald-500 text-white text-base font-medium rounded-xl w-full shadow-lg hover:from-green-500 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-green-300 transition-all transform hover:scale-105">
                            Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-2xl rounded-3xl bg-white/95 backdrop-blur-md border-white/20">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-red-300 to-pink-400 shadow-lg">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-xl w-full shadow-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-red-400 to-pink-500 text-white text-base font-medium rounded-xl w-full shadow-lg hover:from-red-500 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition-all transform hover:scale-105">
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

function showApprovalModal(userId, userName, needsApproval, needsPaymentApproval) {
    let message = 'Apakah Anda yakin ingin menyetujui ';
    if (needsApproval && needsPaymentApproval) {
        message += 'user dan pembayaran untuk ' + userName + '?';
    } else if (needsApproval) {
        message += 'user ' + userName + '?';
    } else if (needsPaymentApproval) {
        message += 'pembayaran untuk ' + userName + '?';
    }
    document.getElementById('approvalMessage').textContent = message;
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