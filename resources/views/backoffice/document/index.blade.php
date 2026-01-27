<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        @include('backoffice.head')
    </head>
    <?php $page="documents" ?>
    <body class="py-5">
        @include('backoffice.menu.navbar-modile')
        <div class="flex">
            @include('backoffice.menu.navbar')
            <div class="content">
                <div class="top-bar">
                    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Application</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Documents Center</li>
                        </ol>
                    </nav>
                    @include('backoffice.menu.account_menu')
                </div>

                <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Document Management
                    </h2>
                    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                        <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#upload-modal">
                            <i data-lucide="upload-cloud" class="w-4 h-4 mr-2"></i> Upload New Document
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                        <form action="{{ route('documents') }}" method="GET" class="flex items-center gap-2">
                            <select name="project_id" class="form-select w-64" onchange="this.form.submit()">
                                <option value="">All Projects</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->code }} : {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if(request('project_id'))
                                <a href="{{ route('documents') }}" class="btn btn-outline-secondary">Clear</a>
                            @endif
                        </form>
                    </div>

                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">FILE NAME</th>
                                    <th class="whitespace-nowrap">RELATED TO</th>
                                    <th class="whitespace-nowrap">TYPE</th>
                                    <th class="whitespace-nowrap">UPLOADED BY</th>
                                    <th class="whitespace-nowrap">DATE</th>
                                    <th class="whitespace-nowrap text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $doc)
                                <tr class="intro-x">
                                    <td>
                                        <div class="flex items-center">
                                            <i data-lucide="file" class="w-4 h-4 mr-2 text-slate-500"></i>
                                            <a href="{{ asset($doc->file_path) }}" target="_blank" class="font-medium text-primary underline">
                                                {{ $doc->file_name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        @if($doc->documentable_type == 'App\Models\Project')
                                            @php $p = \App\Models\Project::find($doc->documentable_id); @endphp
                                            <div class="text-slate-500">{{ $p ? $p->name : 'Unknown Project' }}</div>
                                        @else
                                            <span class="py-1 px-2 rounded-full bg-slate-200 text-xs">General</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="uppercase text-xs font-bold">{{ $doc->file_type }}</span>
                                    </td>
                                    <td>
                                        <div class="text-slate-500">{{ $doc->uploader->name ?? 'System' }}</div>
                                    </td>
                                    <td>
                                        {{ $doc->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center mr-3 text-primary" href="{{ asset($doc->file_path) }}" download>
                                                <i data-lucide="download" class="w-4 h-4 mr-1"></i> Download
                                            </a>
                                            <form action="{{ route('documents.delete') }}" method="POST" onsubmit="return confirm('Delete this file?');">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $doc->id }}">
                                                <button class="flex items-center text-danger"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                        <nav class="w-full sm:w-auto sm:mr-auto">
                            <ul class="pagination">
                                {{ $documents->appends(['project_id' => request('project_id')])->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div id="upload-modal" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Upload Document</h2>
                    </div>
                    <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-12">
                                <label class="form-label">Select File</label>
                                <input type="file" name="file" class="form-control p-2" required>
                                <div class="text-xs text-slate-500 mt-1">Max size: 10MB (PDF, JPG, DOCX, etc.)</div>
                            </div>
                            <div class="col-span-12">
                                <label class="form-label">Link to Project (Optional)</label>
                                <select name="project_id" class="form-select">
                                    <option value="">-- General Document (No Project) --</option>
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}">{{ $p->code }} : {{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                            <button type="submit" class="btn btn-primary w-20">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('backoffice.js.js')
        @if (!empty(session('success')))
        <script>
             window.onload = function() {
                // Toastify Success
            };
        </script>
        @endif
    </body>
</html>
