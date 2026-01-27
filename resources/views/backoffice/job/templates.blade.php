<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        @include('backoffice.head')
    </head>
    <?php $page="job" ?>
    <body class="py-5">
        @include('backoffice.menu.navbar-modile')
        <div class="flex">
            @include('backoffice.menu.navbar')
            <div class="content">
                <div class="top-bar">
                    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('job') }}">Job</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Job Templates Library</li>
                        </ol>
                    </nav>
                    @include('backoffice.menu.account_menu')
                </div>

                <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Job Templates Library
                    </h2>
                    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                        <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add-template-modal">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Create New Template
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">TEMPLATE NAME</th>
                                    <th class="whitespace-nowrap">DESCRIPTION</th>
                                    <th class="whitespace-nowrap text-center">EST. HOURS</th>
                                    <th class="whitespace-nowrap text-center">CHECKLIST ITEMS</th>
                                    <th class="whitespace-nowrap">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($templates as $item)
                                <tr class="intro-x">
                                    <td>
                                        <div class="font-medium">{{ $item->title }}</div>
                                        <div class="text-xs text-slate-500">Created: {{ $item->created_at->format('d/m/Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="text-slate-500">{{ Str::limit($item->description, 50) }}</div>
                                    </td>
                                    <td class="text-center">
                                        {{ $item->estimated_hours }} Hrs
                                    </td>
                                    <td class="text-center">
                                        @if(!empty($item->checklist))
                                            <span class="px-2 py-1 rounded-full bg-slate-100 text-xs">
                                                {{ count($item->checklist) }} Steps
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <form action="{{ route('job.template_delete') }}" method="POST" onsubmit="return confirm('Delete this template?');">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <button class="flex items-center text-danger"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="add-template-modal" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Create Job Template</h2>
                    </div>
                    <form action="{{ route('job.template_create') }}" method="POST">
                        @csrf
                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-12">
                                <label for="title" class="form-label">Template Name</label>
                                <input id="title" type="text" name="title" class="form-control" placeholder="e.g. Install CCTV (Standard)" required>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label for="est_hours" class="form-label">Estimated Hours</label>
                                <input id="est_hours" type="number" name="estimated_hours" class="form-control" placeholder="0">
                            </div>
                            <div class="col-span-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Brief description of this job type..."></textarea>
                            </div>

                            <div class="col-span-12 border-t border-slate-200 pt-4 mt-2">
                                <label class="form-label font-bold">Standard Checklist</label>
                                <div id="checklist-container">
                                    <div class="flex gap-2 mb-2">
                                        <input type="text" name="checklist[]" class="form-control" placeholder="Step 1">
                                    </div>
                                    <div class="flex gap-2 mb-2">
                                        <input type="text" name="checklist[]" class="form-control" placeholder="Step 2">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="addChecklistItem()">
                                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Add Step
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                            <button type="submit" class="btn btn-primary w-20">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('backoffice.js.js')

        <script>
            // Script สำหรับเพิ่มช่อง Checklist
            function addChecklistItem() {
                const container = document.getElementById('checklist-container');
                const div = document.createElement('div');
                div.className = 'flex gap-2 mb-2';
                div.innerHTML = `
                    <input type="text" name="checklist[]" class="form-control" placeholder="Next step...">
                    <button type="button" class="btn btn-danger w-10" onclick="this.parentElement.remove()">x</button>
                `;
                container.appendChild(div);
            }
        </script>

        @if (!empty(session('success')))
        <script>
             window.onload = function() {
                // Toastify หรือ SweetAlert ตาม Theme
                // alert('Template Created!');
            };
        </script>
        @endif
    </body>
</html>
