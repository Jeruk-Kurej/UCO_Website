/**
 * UCO Image Preview Helpers
 * Centralised, consistent image upload preview for all pages.
 *
 * Usage (Full dropzone mode):
 *   initImagePreview('photo', 'my-preview', 10, false)
 *
 * Usage (Side-by-side mode — logo / profile):
 *   initImagePreview('logo', 'logo-preview', 2, true)
 *
 * The component IDs follow the convention:
 *   {previewId}-placeholder, {previewId}-result, {previewId}-img,
 *   {previewId}-info, {previewId}-filename, {previewId}-filesize,
 *   {previewId}-wrapper, {previewId}-arrow, {previewId}-cancel
 */

function ucoInitImagePreview(inputId, previewId, maxSizeMB = 10, sideBySide = false) {
    const input = document.getElementById(inputId);
    if (!input) return;

    input.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            ucoPreviewError('Please select a valid image file (JPG, PNG, GIF).');
            e.target.value = '';
            return;
        }

        if (file.size > maxSizeMB * 1024 * 1024) {
            ucoPreviewError(`File is too large. Maximum size is ${maxSizeMB}MB.`);
            e.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (ev) {
            if (sideBySide) {
                ucoShowSideBySide(previewId, ev.target.result);
            } else {
                ucoShowFullPreview(previewId, ev.target.result, file);
            }
        };
        reader.readAsDataURL(file);
    });
}

function ucoShowSideBySide(previewId, src) {
    const img     = document.getElementById(`${previewId}-img`);
    const wrapper = document.getElementById(`${previewId}-wrapper`);
    const arrow   = document.getElementById(`${previewId}-arrow`);
    const cancel  = document.getElementById(`${previewId}-cancel`);
    if (!img || !wrapper) return;

    img.src = src;
    [wrapper, arrow].forEach(el => {
        if (!el) return;
        el.classList.remove('hidden');
        el.classList.add('flex');
    });
    if (cancel) cancel.classList.remove('hidden');

    // Animate in
    wrapper.style.opacity = '0';
    wrapper.style.transform = 'translateX(-10px)';
    requestAnimationFrame(() => {
        wrapper.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        wrapper.style.opacity = '1';
        wrapper.style.transform = 'translateX(0)';
    });
}

function ucoShowFullPreview(previewId, src, file) {
    const placeholder = document.getElementById(`${previewId}-placeholder`);
    const result      = document.getElementById(`${previewId}-result`);
    const img         = document.getElementById(`${previewId}-img`);
    const info        = document.getElementById(`${previewId}-info`);
    const filename    = document.getElementById(`${previewId}-filename`);
    const filesize    = document.getElementById(`${previewId}-filesize`);

    if (img) img.src = src;

    // Fade out placeholder, fade in preview
    if (placeholder) {
        placeholder.style.transition = 'opacity 0.2s ease';
        placeholder.style.opacity = '0';
        setTimeout(() => placeholder.classList.add('hidden'), 200);
    }
    if (result) {
        result.classList.remove('hidden');
        requestAnimationFrame(() => {
            result.style.transition = 'opacity 0.3s ease';
            result.style.opacity = '1';
        });
    }

    // Show file info bar
    if (info)     { info.classList.remove('hidden'); info.classList.add('flex'); }
    if (filename) filename.textContent = file.name;
    if (filesize) filesize.textContent = ucoFormatSize(file.size);
}

function ucoCancelPreview(previewId, inputId) {
    const input = document.getElementById(inputId);
    if (input) input.value = '';

    // Side-by-side
    const wrapper = document.getElementById(`${previewId}-wrapper`);
    const arrow   = document.getElementById(`${previewId}-arrow`);
    const cancel  = document.getElementById(`${previewId}-cancel`);
    const img     = document.getElementById(`${previewId}-img`);
    if (wrapper) { wrapper.classList.add('hidden'); wrapper.classList.remove('flex'); wrapper.style.opacity = ''; wrapper.style.transform = ''; }
    if (arrow)   { arrow.classList.add('hidden');   arrow.classList.remove('flex'); }
    if (cancel)  cancel.classList.add('hidden');
    if (img)     img.src = '';

    // Full preview
    const placeholder = document.getElementById(`${previewId}-placeholder`);
    const result      = document.getElementById(`${previewId}-result`);
    const info        = document.getElementById(`${previewId}-info`);
    if (result)      { result.classList.add('hidden'); result.style.opacity = '0'; }
    if (placeholder) { placeholder.classList.remove('hidden'); placeholder.style.opacity = '1'; }
    if (info)        { info.classList.add('hidden'); info.classList.remove('flex'); }
}

function ucoHandleDrop(event, inputId, previewId, maxSizeMB) {
    event.preventDefault();
    const dropzone = event.currentTarget;
    dropzone.classList.remove('!border-uco-orange-400', '!bg-uco-orange-50/50');

    const file = event.dataTransfer?.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        ucoPreviewError('Please drop a valid image file (JPG, PNG, GIF).');
        return;
    }
    if (file.size > maxSizeMB * 1024 * 1024) {
        ucoPreviewError(`File too large. Maximum size is ${maxSizeMB}MB.`);
        return;
    }

    // Assign to file input
    try {
        const dt = new DataTransfer();
        dt.items.add(file);
        const input = document.getElementById(inputId);
        if (input) input.files = dt.files;
    } catch (_) { /* Safari fallback - still preview */ }

    const reader = new FileReader();
    reader.onload = (e) => ucoShowFullPreview(previewId, e.target.result, file);
    reader.readAsDataURL(file);
}

function ucoFormatSize(bytes) {
    if (bytes < 1024)           return bytes + ' B';
    if (bytes < 1024 * 1024)    return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function ucoPreviewError(message) {
    const existing = document.getElementById('uco-preview-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'uco-preview-toast';
    toast.className = [
        'fixed top-5 right-5 z-[9999] flex items-center gap-3',
        'px-5 py-3.5 bg-red-600 text-white rounded-xl shadow-2xl',
        'text-sm font-medium max-w-sm pointer-events-none',
    ].join(' ');
    toast.innerHTML = `<i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i><span>${message}</span>`;

    // Animate in
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(20px)';
    document.body.appendChild(toast);
    requestAnimationFrame(() => {
        toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    });

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(20px)';
        setTimeout(() => toast.remove(), 350);
    }, 3500);
}

// Expose globally
window.ucoInitImagePreview = ucoInitImagePreview;
window.ucoCancelPreview    = ucoCancelPreview;
window.ucoHandleDrop       = ucoHandleDrop;
