@extends('include.master')
@section('page','Home')
@section('content')

<style>
    .dashboard-compose-actions { gap: 12px; }
    .dashboard-post-card { margin-bottom: 18px !important; }
    .dashboard-pagination .pagination { gap: 6px; margin-bottom: 0; }
    .dashboard-pagination .page-link {
        border: 0;
        border-radius: 12px !important;
        min-width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--theme-color);
        background: #f3f4f8;
        font-size: 12px;
        font-weight: 700;
    }
    .dashboard-pagination .page-item.active .page-link {
        background: var(--theme-color);
        color: #fff;
    }
    .dashboard-pagination .page-item.disabled .page-link { opacity: .45; }
    .post-privacy-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #f3f4f8;
        color: #6b7280;
        font-size: 10px;
        font-weight: 700;
    }
</style>

<div class="main-content right-chat-active">
    <div class="middle-sidebar-bottom">
        <div class="middle-sidebar-left">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12">

                    {{-- ============ CREATE POST ============ --}}
                    <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3">
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
                            @csrf

                            <textarea name="content"
                                      class="w-100 border-0 rounded-xxl p-3 font-xssss text-grey-700 bg-greylight"
                                      rows="3"
                                      placeholder="Share an update">{{ old('content') }}</textarea>

                            <div class="mt-3" id="mediaPreview"></div>

                            <div class="d-flex flex-wrap align-items-center mt-3 pt-3 border-top dashboard-compose-actions">
                                <div class="me-auto d-flex flex-wrap align-items-center dashboard-compose-actions">
                                    <label class="btn btn-sm bg-greylight rounded-xxl px-3 py-2 font-xssss fw-600 text-grey-700 cursor-pointer mb-0">
                                        <i class="feather-image text-success me-1"></i> Add Media
                                        <input type="file" name="media[]" id="mediaInput" multiple
                                               accept="image/*,video/*" class="d-none">
                                    </label>
                                    <button type="button" onclick="clearAllMedia()" 
                                            class="btn btn-sm btn-outline-danger font-xssss ms-2 d-none" 
                                            id="clearAllBtn">Clear All</button>
                                    <span class="font-xssss text-grey-500 ms-2" id="fileCount"></span>
                                </div>
                                <div class="d-flex align-items-center dashboard-compose-actions">
                                    <select name="privacy" class="form-select form-select-sm w-auto me-2 font-xssss">
                                        <option value="public">Public</option>
                                        <option value="followers">Followers only</option>
                                    </select>
                                    <button type="submit" class="btn bg-current text-white font-xssss fw-600 rounded-xxl px-3">Post</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const mediaInput = document.getElementById('mediaInput');
                        const mediaPreview = document.getElementById('mediaPreview');
                        const clearAllBtn = document.getElementById('clearAllBtn');
                        const fileCount = document.getElementById('fileCount');
                        const postForm = document.getElementById('postForm');
                        let selectedFiles = [];
                        
                        mediaInput.addEventListener('change', function(e) { handleFileSelection(e.target.files); });
                        
                        function handleFileSelection(files) {
                            const validTypes = ['image/jpeg','image/jpg','image/png','image/gif','image/webp','video/mp4','video/mov','video/avi','video/quicktime','video/x-msvideo'];
                            const maxFiles = 4, maxSize = 20*1024*1024;
                            const newFiles = Array.from(files);
                            if (selectedFiles.length + newFiles.length > maxFiles) { alert(`Maximum ${maxFiles} files allowed.`); return; }
                            newFiles.forEach(file => {
                                if (!validTypes.includes(file.type)) { alert(`"${file.name}" is not supported.`); return; }
                                if (file.size > maxSize) { alert(`"${file.name}" is too large.`); return; }
                                selectedFiles.push(file);
                            });
                            updatePreview(); updateFileInput();
                        }
                        
                        function updatePreview() {
                            mediaPreview.innerHTML = '';
                            if (selectedFiles.length === 0) { clearAllBtn.classList.add('d-none'); fileCount.textContent = ''; return; }
                            fileCount.textContent = `${selectedFiles.length} file(s) selected`;
                            clearAllBtn.classList.remove('d-none');
                            const grid = document.createElement('div'); grid.className = 'row g-2';
                            selectedFiles.forEach((file, index) => {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const col = document.createElement('div'); col.className = 'col-6 col-md-3';
                                    const isImage = file.type.startsWith('image/');
                                    col.innerHTML = `<div class="position-relative"><div class="rounded-3 overflow-hidden border bg-light" style="height:120px;">${isImage ? `<img src="${e.target.result}" class="w-100 h-100" style="object-fit:cover;">` : `<div class="w-100 h-100 bg-dark d-flex align-items-center justify-content-center"><i class="feather-play text-white fs-3"></i></div>`}</div><button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 rounded-circle" onclick="removeFile(${index})" style="width:20px;height:20px;padding:0;font-size:14px;transform:translate(30%,-30%);">&times;</button></div>`;
                                    grid.appendChild(col);
                                };
                                reader.readAsDataURL(file);
                            });
                            mediaPreview.appendChild(grid);
                        }
                        
                        window.removeFile = function(i) { selectedFiles.splice(i,1); updatePreview(); updateFileInput(); };
                        window.clearAllMedia = function() { if(confirm('Remove all files?')) { selectedFiles=[]; updatePreview(); updateFileInput(); } };
                        function updateFileInput() { const dt = new DataTransfer(); selectedFiles.forEach(f => dt.items.add(f)); mediaInput.files = dt.files; }
                        
                        postForm.addEventListener('submit', function(e) {
                            const content = this.querySelector('textarea[name="content"]').value.trim();
                            if (content === '' && selectedFiles.length === 0) { e.preventDefault(); alert('Please add some text or media.'); return; }
                            updateFileInput();
                        });
                    });
                    </script>

                    {{-- ============ FEED POSTS ============ --}}
                    @forelse($posts as $post)
                    <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 dashboard-post-card" id="post-{{ $post->id }}">
                        <div class="d-flex mb-3 align-items-center">
                            <a href="{{ route('user.profile', $post->user->username) }}">
                                <img src="{{ $post->user->profile_picture_url }}"
                                     class="rounded-circle me-2" style="width:45px;height:45px;object-fit:cover;">
                            </a>
                            <div>
                                <a href="{{ route('user.profile', $post->user->username) }}" class="text-decoration-none">
                                    <h4 class="fw-700 font-xssss mb-0 text-grey-900">{{ $post->user->name }}</h4>
                                </a>
                                <span class="font-xssss text-grey-500">{{ $post->created_at->diffForHumans() }}</span>
                                <span class="post-privacy-pill ms-2">
                                    <i class="{{ in_array($post->privacy, ['followers', 'friends'], true) ? 'feather-users' : 'feather-globe' }}"></i>
                                    {{ in_array($post->privacy, ['followers', 'friends'], true) ? 'Followers only' : 'Public' }}
                                </span>
                            </div>
                            
                            @if($post->user_id === auth()->id())
                            <div class="ms-auto">
                                <a href="#" class="ms-auto" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti-more-alt text-grey-900 btn-round-md bg-greylight font-xss"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end p-3 rounded-xxl border-0 shadow-lg">
                                    <li>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger font-xssss" onclick="return confirm('Delete this post?')">
                                                <i class="feather-trash-2 me-2"></i> Delete Post
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            @endif
                        </div>

                        @if($post->content)
                        <p class="font-xssss text-grey-700 lh-26 mb-3">{{ $post->content }}</p>
                        @endif

                        @if($post->media->count())
                        <div class="row g-2 mt-2">
                            @foreach($post->media as $media)
                                <div class="col-12 col-md-{{ $post->media->count() == 1 ? '12' : ($post->media->count() == 2 ? '6' : '4') }} p-1">
                                    @if($media->file_type === 'image')
                                        <img src="{{ asset('storage/'.$media->file_path) }}"
                                             class="rounded-3 w-100"
                                             style="{{ $post->media->count() > 1 ? 'height:250px;' : 'max-height:600px;' }} object-fit:cover; cursor:pointer;"
                                             onclick="openMediaModal('{{ asset('storage/'.$media->file_path) }}', 'image')">
                                    @else
                                        <video controls class="rounded-3 w-100" style="max-height:500px;">
                                            <source src="{{ asset('storage/'.$media->file_path) }}" type="{{ $media->mime_type }}">
                                        </video>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Livewire Post Interactions --}}
                        @auth
                        <livewire:post-interaction :post="$post" :key="'feed-post-'.$post->id" />
                        @endauth
                    </div>
                    @empty
                        <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3 text-center">
                            <img src="{{ asset('images/gaming/empty-state.png') }}" alt="No posts" class="mx-auto mb-3 rounded-xxl" style="max-width:200px;opacity:0.8;">
                            <h5 class="fw-700 font-xss text-grey-900 mb-2">Your feed is empty</h5>
                            <p class="font-xssss text-grey-500 mb-3">Follow other players to see their posts here!</p>
                            <a href="{{ route('find-players') }}" class="btn bg-current text-white font-xssss fw-600 rounded-xxl px-4 py-2">
                                <i class="feather-search me-1"></i> Find Players
                            </a>
                        </div>
                    @endforelse

                    {{-- Pagination --}}
                    @if($posts->hasPages())
                    <div class="d-flex justify-content-center mt-4 mb-5 dashboard-pagination">
                        {{ $posts->links('pagination::bootstrap-5') }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Media Modal --}}
<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid rounded" style="max-height:70vh;">
                <video id="modalVideo" controls class="w-100 rounded" style="display:none;"></video>
            </div>
        </div>
    </div>
</div>

<script>
function openMediaModal(src, type) {
    const modalEl = document.getElementById('mediaModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const img = document.getElementById('modalImage'), vid = document.getElementById('modalVideo');
    if (type === 'image') { img.src = src; img.style.display='block'; vid.style.display='none'; }
    else { vid.src = src; vid.style.display='block'; img.style.display='none'; }
    modal.show();
}
</script>

@endsection
