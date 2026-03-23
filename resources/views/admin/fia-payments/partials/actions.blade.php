<div class="flex space-x-2">
    <button onclick="viewPayment({{ $payment->id }})" class="text-blue-600 hover:text-blue-800" title="View">
        <i class="fas fa-eye"></i>
    </button>
    <button onclick="editPayment({{ $payment->id }})" class="text-green-600 hover:text-green-800" title="Edit">
        <i class="fas fa-edit"></i>
    </button>
    <button onclick="deletePayment({{ $payment->id }})" class="text-red-600 hover:text-red-800" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>
