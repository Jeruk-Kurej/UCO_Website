function ucoInitImagePreview(inputId, previewId, maxSizeMB = 10, sideBySide = false) {
    const input = document.getElementById(inputId);
    if (!input) return;

    input.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);
        if (files.length === 0) return;

        const isMultiple = input.hasAttribute('multiple');

        // Validation
        for (const file of files) {
            if (!file.type.startsWith('image/')) {
                ucoPreviewError('Please select valid image files (JPG, PNG, GIF).');
                e.target.value = '';
                ucoCancelPreview(previewId, inputId);
                return;
            }

            if (file.size > maxSizeMB * 1024 * 1024) {
                ucoPreviewError(`One of the files is too large. Maximum size is ${maxSizeMB}MB.`);
                e.target.value = '';
                ucoCancelPreview(previewId, inputId);
                return;
            }
        }

        if (isMultiple) {
            ucoShowMultiplePreviews(previewId, files);
        } else {
            const reader = new FileReader();
            reader.onload = function (ev) {
                if (sideBySide) {
                    ucoShowSideBySide(previewId, ev.target.result);
                } else {
                    ucoShowFullPreview(previewId, ev.target.result, files[0]);
                }
            };
            reader.readAsDataURL(files[0]);
        }
    });
}

function ucoShowMultiplePreviews(previewId, files) {
    const placeholder = document.getElementById(`${previewId}-placeholder`);
    const gallery     = document.getElementById(`${previewId}-gallery`);
    const grid        = document.getElementById(`${previewId}-grid`);
    const multiInfo   = document.getElementById(`${previewId}-multi-info`);
    const countBadge  = document.getElementById(`${previewId}-count`);
    const overlay     = document.getElementById(`${previewId}-overlay`);

    if (!gallery || !grid) return;

    // Reset grid
    grid.innerHTML = '';

    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const item = document.createElement('div');
            item.className = 'relative aspect-square rounded-lg overflow-hidden border border-white/50 shadow-sm animate-in fade-in zoom-in duration-300';
            item.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                <div class="absolute bottom-1 right-1 bg-black/60 backdrop-blur-sm px-1 rounded text-[8px] text-white font-bold uppercase tracking-tighter">
                    #${index + 1}
                </div>
            `;
            grid.appendChild(item);
        };
        reader.readAsDataURL(file);
    });

    // Toggle Visibility
    if (placeholder) placeholder.classList.add('hidden');
    gallery.classList.remove('hidden');
    if (multiInfo) {
        multiInfo.classList.remove('hidden');
        multiInfo.classList.add('flex');
    }
    if (countBadge) countBadge.textContent = `${files.length} PHOTOS SELECTED`;
    if (overlay) overlay.classList.remove('hidden');
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
    const overlay     = document.getElementById(`${previewId}-overlay`);

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
    if (overlay) overlay.classList.remove('hidden');
}

function ucoCancelPreview(previewId, inputId) {
    const input = document.getElementById(inputId);
    if (input) input.value = '';

    // Elements
    const elements = {
        wrapper: document.getElementById(`${previewId}-wrapper`),
        arrow: document.getElementById(`${previewId}-arrow`),
        cancel: document.getElementById(`${previewId}-cancel`),
        img: document.getElementById(`${previewId}-img`),
        placeholder: document.getElementById(`${previewId}-placeholder`),
        result: document.getElementById(`${previewId}-result`),
        info: document.getElementById(`${previewId}-info`),
        gallery: document.getElementById(`${previewId}-gallery`),
        grid: document.getElementById(`${previewId}-grid`),
        multiInfo: document.getElementById(`${previewId}-multi-info`),
        overlay: document.getElementById(`${previewId}-overlay`)
    };

    // Side-by-side cleanup
    if (elements.wrapper) { 
        elements.wrapper.classList.add('hidden'); 
        elements.wrapper.classList.remove('flex'); 
        elements.wrapper.style.opacity = ''; 
        elements.wrapper.style.transform = ''; 
    }
    if (elements.arrow)   { elements.arrow.classList.add('hidden');   elements.arrow.classList.remove('flex'); }
    if (elements.cancel)  elements.cancel.classList.add('hidden');
    if (elements.img)     elements.img.src = '';

    // Full/Multi preview cleanup
    if (elements.result)      { elements.result.classList.add('hidden'); elements.result.style.opacity = '0'; }
    if (elements.placeholder) { elements.placeholder.classList.remove('hidden'); elements.placeholder.style.opacity = '1'; }
    if (elements.info)        { elements.info.classList.add('hidden'); elements.info.classList.remove('flex'); }
    if (elements.gallery)     elements.gallery.classList.add('hidden');
    if (elements.grid)        elements.grid.innerHTML = '';
    if (elements.multiInfo)   { elements.multiInfo.classList.add('hidden'); elements.multiInfo.classList.remove('flex'); }
    if (elements.overlay)     elements.overlay.classList.add('hidden');
}

function ucoHandleDrop(event, inputId, previewId, maxSizeMB, isMultiple = false) {
    event.preventDefault();
    const dropzone = event.currentTarget;
    dropzone.classList.remove('!border-uco-orange-400', '!bg-uco-orange-50/50');

    const files = Array.from(event.dataTransfer?.files || []);
    if (files.length === 0) return;

    // Filter images & size
    const validFiles = files.filter(file => {
        if (!file.type.startsWith('image/')) {
            ucoPreviewError(`Skipped non-image file: ${file.name}`);
            return false;
        }
        if (file.size > maxSizeMB * 1024 * 1024) {
            ucoPreviewError(`File too large: ${file.name}`);
            return false;
        }
        return true;
    });

    if (validFiles.length === 0) return;

    const input = document.getElementById(inputId);
    if (!input) return;

    try {
        const dt = new DataTransfer();
        if (isMultiple) {
            validFiles.forEach(f => dt.items.add(f));
        } else {
            dt.items.add(validFiles[0]);
        }
        input.files = dt.files;
        
        // Trigger manual change if needed, otherwise fire preview logic
        if (isMultiple) {
            ucoShowMultiplePreviews(previewId, validFiles);
        } else {
            const reader = new FileReader();
            reader.onload = (e) => ucoShowFullPreview(previewId, e.target.result, validFiles[0]);
            reader.readAsDataURL(validFiles[0]);
        }
    } catch (_) { /* Safari fallback */ }
}

function ucoFormatSize(bytes) {
    if (bytes < 1024)           return bytes + ' B';
    if (bytes < 1024 * 1024)    return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function ucoPreviewError(message) {
    if (window.showToast) {
        window.showToast(message, 'error');
    } else {
        console.error('UCO Preview Error:', message);
    }
}

// Expose globally
window.ucoInitImagePreview = ucoInitImagePreview;
window.ucoCancelPreview    = ucoCancelPreview;
window.ucoHandleDrop       = ucoHandleDrop;

