@extends('layouts.admin')

@section('title', isset($blog) ? 'Edit Blog — ' . $blog->title : 'New Blog Post')

@push('head')
{{-- TinyMCE CDN (free self-hosted version) --}}
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
<div class="admin-page-header">
    <div>
        <h1>{{ isset($blog) ? 'Edit Blog Post' : 'New Blog Post' }}</h1>
        <p class="text-muted">Write SEO-optimised content for MyManaliTrip readers</p>
    </div>
    <div class="header-actions">
        @if(isset($blog))
        <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="btn btn-outline">
            <i class="fas fa-eye"></i> View Live
        </a>
        @endif
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-ghost">← Back to Blogs</a>
    </div>
</div>

<form action="{{ isset($blog) ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}"
      method="POST" enctype="multipart/form-data" id="blogForm">
    @csrf
    @if(isset($blog)) @method('PUT') @endif

    <div class="blog-editor-layout">

        {{-- ═══════════════════════════════════════════════════════ --}}
        {{-- LEFT COLUMN — Main Content                              --}}
        {{-- ═══════════════════════════════════════════════════════ --}}
        <div class="editor-main">

            {{-- Title --}}
            <div class="editor-card">
                <div class="form-group">
                    <label for="title" class="form-label required">Blog Title</label>
                    <input type="text" id="title" name="title"
                           class="form-control form-control-xl @error('title') is-invalid @enderror"
                           value="{{ old('title', $blog->title ?? '') }}"
                           placeholder="e.g. Manali Trip Cost 2025 – Complete Budget Guide"
                           oninput="updateSlugPreview(this.value)">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <p class="form-hint">
                        <span id="titleCharCount">0</span>/60 chars recommended for SEO title
                    </p>
                </div>

                {{-- Slug --}}
                <div class="form-group">
                    <label class="form-label">URL Slug</label>
                    <div class="slug-preview-wrap">
                        <span class="slug-base">mymanalitrip.com/manali-travel-guide/</span>
                        <input type="text" name="slug" id="slugInput"
                               class="form-control slug-input @error('slug') is-invalid @enderror"
                               value="{{ old('slug', $blog->slug ?? '') }}"
                               placeholder="auto-generated-from-title">
                    </div>
                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Excerpt --}}
                <div class="form-group">
                    <label for="excerpt" class="form-label">Excerpt / Short Description</label>
                    <textarea name="excerpt" id="excerpt" rows="3"
                              class="form-control @error('excerpt') is-invalid @enderror"
                              placeholder="Write a 1–2 sentence summary. Shown on blog cards."
                              maxlength="500" oninput="countExcerpt(this)">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                    <p class="form-hint"><span id="excerptCount">0</span>/500 chars</p>
                    @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Rich Text Editor --}}
            <div class="editor-card">
                <label class="form-label required">Blog Content</label>
                <p class="form-hint mb-2">Use the toolbar to format text, insert images, add tables, and embed YouTube videos.</p>

                <textarea name="content" id="contentEditor">{{ old('content', $blog->content ?? '') }}</textarea>
                @error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- SEO Preview Card --}}
            <div class="editor-card seo-preview-card">
                <h3 class="card-sub-title"><i class="fas fa-search"></i> Google Search Preview</h3>
                <div class="google-preview">
                    <div class="gp-url">mymanalitrip.com › manali-travel-guide › <span id="gpSlug">your-post-slug</span></div>
                    <div class="gp-title" id="gpTitle">Your SEO Title Will Appear Here</div>
                    <div class="gp-desc" id="gpDesc">Your meta description will appear here — keep it between 120–155 characters for best results.</div>
                </div>

                <div class="seo-score-bar" id="seoScoreBar">
                    <div class="score-label">SEO Score: <strong id="seoScoreText">Calculating...</strong></div>
                    <div class="score-track">
                        <div class="score-fill" id="scoreFill"></div>
                    </div>
                    <ul class="seo-checklist" id="seoChecklist"></ul>
                </div>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════════════════ --}}
        {{-- RIGHT COLUMN — Meta, Settings, Image                    --}}
        {{-- ═══════════════════════════════════════════════════════ --}}
        <div class="editor-sidebar">

            {{-- Publish Card --}}
            <div class="editor-card sidebar-card">
                <h3 class="card-title"><i class="fas fa-paper-plane"></i> Publish</h3>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" id="statusSelect" onchange="toggleSchedule(this.value)">
                        <option value="draft"     {{ old('status', $blog->status ?? 'draft') === 'draft'     ? 'selected' : '' }}>📝 Draft</option>
                        <option value="published" {{ old('status', $blog->status ?? 'draft') === 'published' ? 'selected' : '' }}>✅ Published</option>
                        <option value="scheduled" {{ old('status', $blog->status ?? 'draft') === 'scheduled' ? 'selected' : '' }}>🕐 Scheduled</option>
                    </select>
                </div>

                <div class="form-group" id="scheduleDateGroup" style="display:none">
                    <label class="form-label">Publish Date & Time</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                           value="{{ old('published_at', isset($blog) && $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control">
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category', $blog->category ?? 'Travel Guide') === $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tags <span class="text-muted">(comma separated)</span></label>
                    <input type="text" name="tags" class="form-control"
                           value="{{ old('tags', isset($blog) && $blog->tags ? implode(', ', $blog->tags) : '') }}"
                           placeholder="Manali, Budget Trip, Volvo Package">
                </div>

                <div class="publish-actions">
                    <button type="submit" name="action" value="publish" class="btn btn-primary btn-block">
                        <i class="fas fa-check"></i>
                        {{ isset($blog) ? 'Update Blog' : 'Publish Blog' }}
                    </button>
                    <button type="submit" name="action" value="draft" class="btn btn-outline btn-block mt-2">
                        Save as Draft
                    </button>
                </div>
            </div>

            {{-- Featured Image --}}
            <div class="editor-card sidebar-card">
                <h3 class="card-title"><i class="fas fa-image"></i> Featured Image</h3>

                <div class="image-upload-zone" id="imageUploadZone" onclick="document.getElementById('featured_image').click()">
                    @if(isset($blog) && $blog->featured_image)
                        <img src="{{ Storage::url($blog->featured_image) }}" class="img-preview" id="imgPreview" alt="Featured">
                    @else
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click to upload</span>
                            <small>JPG, PNG, WebP — max 2MB</small>
                        </div>
                        <img src="" class="img-preview" id="imgPreview" style="display:none">
                    @endif
                </div>
                <input type="file" name="featured_image" id="featured_image" accept="image/*" style="display:none" onchange="previewImage(this)">

                <div class="form-group mt-2">
                    <label class="form-label">Image Alt Text <span class="text-muted">(SEO)</span></label>
                    <input type="text" name="featured_image_alt" class="form-control"
                           value="{{ old('featured_image_alt', $blog->featured_image_alt ?? '') }}"
                           placeholder="Describe the image for Google">
                </div>
            </div>

            {{-- SEO Meta Panel --}}
            <div class="editor-card sidebar-card seo-panel">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> SEO Settings</h3>

                <div class="form-group">
                    <label class="form-label">
                        Meta Title
                        <span class="form-hint-inline" id="metaTitleCount">0/60</span>
                    </label>
                    <input type="text" name="meta_title" class="form-control" id="metaTitleInput"
                           maxlength="70"
                           value="{{ old('meta_title', $blog->meta_title ?? '') }}"
                           placeholder="Leave blank to auto-use post title | MyManaliTrip"
                           oninput="updateMetaPreview()">
                    <p class="form-hint">Ideal: 50–60 characters</p>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Meta Description
                        <span class="form-hint-inline" id="metaDescCount">0/155</span>
                    </label>
                    <textarea name="meta_description" class="form-control" id="metaDescInput" rows="3"
                              maxlength="160"
                              placeholder="Describe what this page is about in 120–155 characters."
                              oninput="updateMetaPreview()">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                    <p class="form-hint">Appears in Google results below title.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control"
                           value="{{ old('meta_keywords', $blog->meta_keywords ?? '') }}"
                           placeholder="manali trip cost, manali budget 2025">
                    <p class="form-hint">Comma-separated. Not a major ranking factor but good practice.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Canonical URL <span class="text-muted">(optional)</span></label>
                    <input type="url" name="canonical_url" class="form-control"
                           value="{{ old('canonical_url', $blog->canonical_url ?? '') }}"
                           placeholder="Only set if this is a duplicate of another page">
                </div>

                <div class="form-group">
                    <label class="form-label">Schema Type</label>
                    <select name="schema_type" class="form-control">
                        <option value="BlogPosting" {{ old('schema_type', $blog->schema_type ?? 'BlogPosting') === 'BlogPosting' ? 'selected' : '' }}>BlogPosting (default)</option>
                        <option value="Article"    {{ old('schema_type', $blog->schema_type ?? '') === 'Article'    ? 'selected' : '' }}>Article</option>
                        <option value="FAQPage"    {{ old('schema_type', $blog->schema_type ?? '') === 'FAQPage'    ? 'selected' : '' }}>FAQPage</option>
                        <option value="HowTo"      {{ old('schema_type', $blog->schema_type ?? '') === 'HowTo'      ? 'selected' : '' }}>HowTo</option>
                    </select>
                </div>
            </div>

            {{-- OG Image --}}
            <div class="editor-card sidebar-card">
                <h3 class="card-title"><i class="fab fa-facebook"></i> Social / OG Image</h3>
                <p class="form-hint mb-2">Shown when shared on WhatsApp, Facebook, Twitter. Ideal: 1200×630px</p>

                <div class="image-upload-zone small" onclick="document.getElementById('og_image').click()">
                    @if(isset($blog) && $blog->og_image)
                        <img src="{{ Storage::url($blog->og_image) }}" class="img-preview" id="ogPreview">
                    @else
                        <div class="upload-placeholder" id="ogPlaceholder">
                            <i class="fas fa-share-alt"></i>
                            <span>Upload OG Image</span>
                            <small>1200×630 recommended</small>
                        </div>
                        <img src="" class="img-preview" id="ogPreview" style="display:none">
                    @endif
                </div>
                <input type="file" name="og_image" id="og_image" accept="image/*" style="display:none" onchange="previewOg(this)">
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// ── TinyMCE Init ─────────────────────────────────────────────────────────────
tinymce.init({
    selector: '#contentEditor',
    height: 600,
    menubar: true,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'searchreplace', 'visualblocks', 'fullscreen', 'insertdatetime',
        'media', 'table', 'wordcount', 'anchor', 'codesample', 'emoticons',
        'quickbars', 'autosave', 'accordion',
    ],
    toolbar: [
        'undo redo | styles | bold italic underline strikethrough | forecolor backcolor',
        'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table',
        'blockquote codesample accordion | removeformat | fullscreen preview',
    ].join(' | '),
    toolbar_mode: 'wrap',
    content_style: `
        body { font-family: 'DM Sans', sans-serif; font-size: 16px; line-height: 1.7; color: #0d1b2e; max-width: 760px; margin: 0 auto; padding: 20px; }
        h1,h2,h3 { font-family: Georgia, serif; color: #0d1b2e; }
        h2 { font-size: 1.6em; margin-top: 1.5em; border-bottom: 2px solid #f5a623; padding-bottom: 6px; }
        h3 { font-size: 1.3em; margin-top: 1.2em; }
        a { color: #f5a623; }
        blockquote { border-left: 4px solid #f5a623; padding: 12px 20px; background: #f8f6f2; margin: 20px 0; border-radius: 0 8px 8px 0; }
        img { max-width: 100%; border-radius: 8px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #0d1b2e; color: #fff; padding: 10px; }
        td { border: 1px solid #ede9e2; padding: 10px; }
    `,
    // Image upload handler
    images_upload_url: '{{ route('admin.blogs.upload-image') }}',
    images_upload_credentials: true,
    images_upload_handler: function(blobInfo, progress) {
        return new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch('{{ route('admin.blogs.upload-image') }}', {
                method: 'POST',
                body: formData,
            })
            .then(r => r.json())
            .then(data => resolve(data.location))
            .catch(() => reject('Image upload failed'));
        });
    },
    // SEO helpers in content
    quickbars_selection_toolbar: 'bold italic | h2 h3 | blockquote link',
    setup: function(editor) {
        editor.on('input change keyup', function() {
            calculateSeoScore();
        });
    },
    // Auto-save locally every 30s
    autosave_interval: '30s',
    autosave_prefix: 'mmt-blog-{path}{query}-{id}-',
    autosave_restore_when_empty: true,
});

// ── Slug generation ──────────────────────────────────────────────────────────
function updateSlugPreview(title) {
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');

    document.getElementById('slugInput').value = slug;
    document.getElementById('gpSlug').textContent = slug || 'your-post-slug';
    updateMetaPreview();

    const count = title.length;
    document.getElementById('titleCharCount').textContent = count;
    calculateSeoScore();
}

// ── Meta preview ─────────────────────────────────────────────────────────────
function updateMetaPreview() {
    const metaTitle = document.getElementById('metaTitleInput').value ||
                      document.getElementById('title').value;
    const metaDesc  = document.getElementById('metaDescInput').value || '';
    const slug      = document.getElementById('slugInput').value;

    document.getElementById('gpTitle').textContent = metaTitle + (metaTitle ? ' | MyManaliTrip' : '');
    document.getElementById('gpDesc').textContent  = metaDesc || 'Your meta description will appear here.';
    document.getElementById('gpSlug').textContent  = slug || 'your-post-slug';

    // Character counts
    document.getElementById('metaTitleCount').textContent = (document.getElementById('metaTitleInput').value.length) + '/60';
    document.getElementById('metaDescCount').textContent  = metaDesc.length + '/155';
}

// ── SEO Score Calculator ──────────────────────────────────────────────────────
function calculateSeoScore() {
    const title    = document.getElementById('title').value;
    const metaDesc = document.getElementById('metaDescInput').value;
    const metaTitle= document.getElementById('metaTitleInput').value;
    const excerpt  = document.getElementById('excerpt').value;
    const slug     = document.getElementById('slugInput').value;

    let score = 0;
    const checks = [];

    // Title checks
    if (title.length >= 30 && title.length <= 70) { score += 15; checks.push({ ok: true,  text: 'Title length is good (30–70 chars)' }); }
    else                                           { checks.push({ ok: false, text: `Title should be 30–70 chars (currently ${title.length})` }); }

    // Meta description
    if (metaDesc.length >= 120 && metaDesc.length <= 155) { score += 20; checks.push({ ok: true,  text: 'Meta description is perfect (120–155 chars)' }); }
    else if (metaDesc.length > 0)                          { score += 8;  checks.push({ ok: false, text: `Meta description length: ${metaDesc.length} (ideal: 120–155)` }); }
    else                                                   { checks.push({ ok: false, text: 'Add a meta description' }); }

    // Meta title
    if (metaTitle.length > 0 && metaTitle.length <= 60) { score += 10; checks.push({ ok: true, text: 'Meta title set' }); }
    else if (!metaTitle)                                  { checks.push({ ok: false, text: 'Set a custom meta title (optional but recommended)' }); }

    // Slug
    if (slug && slug.length < 60) { score += 10; checks.push({ ok: true, text: 'URL slug is clean and short' }); }
    else                           { checks.push({ ok: false, text: 'Slug too long or missing' }); }

    // Excerpt
    if (excerpt.length > 50) { score += 10; checks.push({ ok: true, text: 'Excerpt filled in' }); }
    else                      { checks.push({ ok: false, text: 'Add an excerpt (improves click-through rate)' }); }

    // Focus keyword in title (basic check — user should type it in tags)
    const tags = document.querySelector('[name="tags"]').value;
    if (tags) { score += 10; checks.push({ ok: true, text: 'Tags / keywords added' }); }
    else       { checks.push({ ok: false, text: 'Add tags / focus keyword' }); }

    // Image
    const imgSrc = document.getElementById('imgPreview').src;
    if (imgSrc && imgSrc !== window.location.href) { score += 15; checks.push({ ok: true, text: 'Featured image uploaded' }); }
    else                                            { checks.push({ ok: false, text: 'Upload a featured image' }); }

    // Alt text
    const altText = document.querySelector('[name="featured_image_alt"]').value;
    if (altText) { score += 10; checks.push({ ok: true, text: 'Image alt text set (great for image SEO)' }); }
    else          { checks.push({ ok: false, text: 'Add image alt text' }); }

    // ── Render score ─────────────────────────────────────────────
    const fill  = document.getElementById('scoreFill');
    const label = document.getElementById('seoScoreText');
    const list  = document.getElementById('seoChecklist');

    fill.style.width = score + '%';
    fill.style.background = score >= 75 ? '#2d6a4f' : score >= 50 ? '#f5a623' : '#e63946';

    label.textContent = score + '/100 — ' + (score >= 75 ? '✅ Good' : score >= 50 ? '⚠️ Needs Work' : '❌ Poor');
    label.style.color = score >= 75 ? '#2d6a4f' : score >= 50 ? '#f5a623' : '#e63946';

    list.innerHTML = checks.map(c => `
        <li class="seo-check ${c.ok ? 'ok' : 'fail'}">
            <span class="check-icon">${c.ok ? '✓' : '✗'}</span> ${c.text}
        </li>
    `).join('');
}

// ── Image preview ────────────────────────────────────────────────────────────
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('imgPreview');
            const ph  = document.getElementById('uploadPlaceholder');
            img.src = e.target.result;
            img.style.display = 'block';
            if (ph) ph.style.display = 'none';
            calculateSeoScore();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewOg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('ogPreview');
            const ph  = document.getElementById('ogPlaceholder');
            img.src = e.target.result;
            img.style.display = 'block';
            if (ph) ph.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── Excerpt counter ──────────────────────────────────────────────────────────
function countExcerpt(el) {
    document.getElementById('excerptCount').textContent = el.value.length;
}

// ── Scheduled toggle ─────────────────────────────────────────────────────────
function toggleSchedule(val) {
    document.getElementById('scheduleDateGroup').style.display = val === 'scheduled' ? 'block' : 'none';
}

// ── Init ──────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    const title    = document.getElementById('title');
    const metaDesc = document.getElementById('metaDescInput');
    const metaTitle= document.getElementById('metaTitleInput');
    const excerpt  = document.getElementById('excerpt');

    if (title.value)    document.getElementById('titleCharCount').textContent = title.value.length;
    if (excerpt.value)  document.getElementById('excerptCount').textContent   = excerpt.value.length;

    toggleSchedule(document.getElementById('statusSelect').value);
    updateMetaPreview();
    calculateSeoScore();

    // Real-time char counts
    metaDesc.addEventListener('input', updateMetaPreview);
    metaTitle.addEventListener('input', updateMetaPreview);
});
</script>
@endpush
