@extends('layouts.member')

@section('page-title', 'Financial Repository')

@section('content')
<div class="space-y-4 pb-12">
    <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-gray-50">
            <h1 class="text-xl sm:text-2xl font-black text-gray-900">Statements</h1>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 sm:px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Month</th>
                        <th class="px-6 sm:px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($deposits as $deposit)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 sm:px-8 py-5">
                                <p class="text-sm font-black text-gray-900">{{ $deposit->month_name }} {{ $deposit->year }}</p>
                            </td>
                            <td class="px-6 sm:px-8 py-5 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button
                                        type="button"
                                        class="px-4 py-2 bg-[#015425] text-white rounded-md text-xs font-black hover:bg-[#013019] transition"
                                        data-preview-url="{{ $deposit->statement_pdf ? $deposit->statement_pdf : route('member.monthly-deposits.show', $deposit->id) }}"
                                        onclick="window.__openStatementPreview?.(this.getAttribute('data-preview-url'))"
                                    >
                                        Preview
                                    </button>
                                    <a
                                        href="{{ $deposit->statement_pdf ? $deposit->statement_pdf : route('member.monthly-deposits.show', $deposit->id) }}"
                                        class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-md text-xs font-black hover:bg-gray-50 transition"
                                        data-download-url="{{ $deposit->statement_pdf ? $deposit->statement_pdf : route('member.monthly-deposits.show', $deposit->id) }}"
                                        data-download-name="statement_{{ $deposit->year }}_{{ str_pad($deposit->month, 2, '0', STR_PAD_LEFT) }}"
                                        onclick="return window.__downloadStatement?.(this.getAttribute('data-download-url'), this.getAttribute('data-download-name'))"
                                    >
                                        Download
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 sm:px-8 py-20 text-center">
                                <p class="text-sm font-bold text-gray-500">No statements available.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="statement-preview-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="window.__closeStatementPreview?.()"></div>
        <div class="absolute inset-0 p-4 sm:p-8 flex items-center justify-center">
            <div class="w-full max-w-5xl bg-white rounded-lg shadow-2xl overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <p class="text-sm font-black text-gray-900">Statement Preview</p>
                    <button type="button" class="p-2 rounded-md bg-gray-50 hover:bg-gray-100 transition" onclick="window.__closeStatementPreview?.()">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="bg-gray-50" style="height: 75vh;">
                    <iframe id="statement-preview-frame" class="w-full h-full" src="about:blank"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function __extractDriveFileId(url) {
    try {
        const u = new URL(url);
        if (!u.hostname.includes('drive.google.com')) return null;

        const m1 = u.pathname.match(/\/file\/d\/([^\/]+)/);
        if (m1 && m1[1]) return m1[1];

        const id = u.searchParams.get('id');
        if (id) return id;
    } catch (e) {}
    return null;
}

function __drivePreviewUrl(url) {
    const id = __extractDriveFileId(url);
    if (!id) return url;
    return 'https://drive.google.com/file/d/' + id + '/preview';
}

function __driveDownloadUrl(url) {
    const id = __extractDriveFileId(url);
    if (!id) return url;
    return 'https://drive.google.com/uc?export=download&id=' + id;
}

window.__openStatementPreview = function (url) {
    const modal = document.getElementById('statement-preview-modal');
    const frame = document.getElementById('statement-preview-frame');
    if (!modal || !frame || !url) return;

    const previewUrl = __drivePreviewUrl(url);
    frame.src = previewUrl;
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

window.__closeStatementPreview = function () {
    const modal = document.getElementById('statement-preview-modal');
    const frame = document.getElementById('statement-preview-frame');
    if (!modal || !frame) return;

    modal.classList.add('hidden');
    frame.src = 'about:blank';
    document.body.classList.remove('overflow-hidden');
}

window.__downloadStatement = function (url) {
    if (!url) return false;
    const args = Array.from(arguments);
    const name = (args[1] || 'statement');
    const dl = __driveDownloadUrl(url);

    if (dl !== url) {
        window.location.href = dl;
        return false;
    }

    fetch(url, { credentials: 'same-origin' })
        .then((res) => {
            if (!res.ok) throw new Error('Failed');
            return res.text();
        })
        .then((html) => {
            const blob = new Blob([html], { type: 'text/html;charset=utf-8;' });
            const objUrl = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = objUrl;
            a.download = name + '.html';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(objUrl);
        })
        .catch(() => {
            window.location.href = url;
        });

    return false;
}
</script>
@endpush
@endsection
