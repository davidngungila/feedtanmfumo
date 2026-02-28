@extends('layouts.member')

@section('page-title', 'Financial Repository')

@section('content')
<div class="space-y-5 pb-12">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 sm:p-8 text-white">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Statements</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Select a month and preview or download your statement.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
        <div class="p-4 sm:p-6">
            <div class="space-y-3">
                @forelse($deposits as $deposit)
                    <div class="group flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 sm:p-5 rounded-lg border border-gray-100 hover:border-green-200 hover:shadow-md transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-green-50 text-[#015425] flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-base sm:text-lg font-black text-gray-900">{{ $deposit->month_name }} {{ $deposit->year }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 w-full sm:w-auto">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center px-4 py-2 bg-[#015425] text-white rounded-md text-xs font-black hover:bg-[#013019] transition w-full sm:w-auto"
                                data-preview-url="{{ $deposit->statement_pdf ? $deposit->statement_pdf : route('member.monthly-deposits.show', $deposit->id) }}"
                                data-title="{{ $deposit->month_name }} {{ $deposit->year }}"
                                onclick="window.__openStatementPreview?.(this.getAttribute('data-preview-url'), this.getAttribute('data-title'))"
                            >
                                Preview
                            </button>
                            <a
                                href="{{ $deposit->statement_pdf ? $deposit->statement_pdf : route('member.monthly-deposits.show', $deposit->id) }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-md text-xs font-black hover:bg-gray-50 transition w-full sm:w-auto"
                                data-download-url="{{ $deposit->statement_pdf ? $deposit->statement_pdf : route('member.monthly-deposits.show', $deposit->id) }}"
                                data-download-name="statement_{{ $deposit->year }}_{{ str_pad($deposit->month, 2, '0', STR_PAD_LEFT) }}"
                                onclick="return window.__downloadStatement?.(this.getAttribute('data-download-url'), this.getAttribute('data-download-name'))"
                            >
                                Download
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <p class="text-sm font-bold text-gray-500">No statements available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="statement-preview-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="window.__closeStatementPreview?.()"></div>
        <div class="absolute inset-0 p-4 sm:p-8 flex items-center justify-center">
            <div class="w-full max-w-7xl bg-white rounded-lg shadow-2xl overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-lg sm:text-xl font-black text-gray-900" id="statement-preview-title">Statement Preview</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" id="statement-preview-subtitle">Preview</p>
                    </div>
                    <button type="button" class="p-2 rounded-md bg-gray-50 hover:bg-gray-100 transition" onclick="window.__closeStatementPreview?.()">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="bg-gray-50" style="height: 85vh;">
                    <iframe id="statement-preview-frame" class="w-full h-full bg-white" src="about:blank"></iframe>
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
    const titleEl = document.getElementById('statement-preview-title');
    const subEl = document.getElementById('statement-preview-subtitle');
    if (!modal || !frame || !url) return;

    const args = Array.from(arguments);
    const label = (args[1] || 'Statement Preview');

    const previewUrl = __drivePreviewUrl(url);
    frame.src = previewUrl;
    if (titleEl) titleEl.textContent = label;
    if (subEl) subEl.textContent = previewUrl.includes('drive.google.com') ? 'Google Drive Preview' : 'In-App Preview';
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

document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    const modal = document.getElementById('statement-preview-modal');
    if (!modal || modal.classList.contains('hidden')) return;
    window.__closeStatementPreview?.();
});

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
