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
                            <li class="breadcrumb-item active" aria-current="page">Approval Center</li>
                        </ol>
                    </nav>
                    @include('backoffice.menu.account_menu')
                </div>

                <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Waiting for Approval
                    </h2>
                </div>

                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        @if($approvals->count() > 0)
                        <table class="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">JOB TITLE</th>
                                    <th class="whitespace-nowrap">PROJECT / SITE</th>
                                    <th class="whitespace-nowrap text-center">SUBMITTED DATE</th>
                                    <th class="whitespace-nowrap text-center">STATUS</th>
                                    <th class="whitespace-nowrap text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($approvals as $task)
                                <tr class="intro-x">
                                    <td>
                                        <div class="font-medium">{{ $task->title }}</div>
                                        <div class="text-slate-500 text-xs mt-0.5">{{ Str::limit($task->description, 40) }}</div>
                                    </td>
                                    <td>
                                        <div class="font-medium text-primary">{{ $task->project->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500">{{ $task->site->name ?? '-' }}</div>
                                    </td>
                                    <td class="text-center">
                                        {{ $task->updated_at->format('d/m/Y H:i') }}
                                        <div class="text-xs text-slate-400">by {{ $task->assignee_id ?? 'Technician' }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="py-1 px-3 rounded-full text-xs bg-warning text-white cursor-pointer">Waiting Approval</span>
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center gap-2">
                                            <form action="{{ route('job.approval_action') }}" method="POST" onsubmit="return confirm('Confirm Approve?');">
                                                @csrf
                                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                <input type="hidden" name="action" value="approve">
                                                <button class="btn btn-sm btn-success text-white"> <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i> Approve </button>
                                            </form>

                                            <button class="btn btn-sm btn-danger text-white"
                                                    onclick="openRejectModal({{ $task->id }}, '{{ $task->title }}')">
                                                <i data-lucide="x-circle" class="w-4 h-4 mr-1"></i> Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="text-center p-10">
                                <div class="text-slate-500 text-lg">No pending approvals.</div>
                                <div class="text-slate-400 mt-2">Good job! All tasks are verified.</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="reject-modal" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('job.approval_action') }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" id="reject_task_id">
                        <input type="hidden" name="action" value="reject">

                        <div class="modal-header">
                            <h2 class="font-medium text-base mr-auto">Reject Task: <span id="reject_task_title"></span></h2>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger-soft show flex items-center mb-2" role="alert">
                                <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> This task will be sent back for correction.
                            </div>
                            <label class="form-label">Reason / Comment</label>
                            <textarea name="comment" class="form-control" rows="3" placeholder="Please explain what needs to be fixed..." required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                            <button type="submit" class="btn btn-danger w-20">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('backoffice.js.js')

        <script>
            function openRejectModal(id, title) {
                // Set value to modal input
                document.getElementById('reject_task_id').value = id;
                document.getElementById('reject_task_title').innerText = title;

                // Show Modal using Tailwind Toggle (Theme specific)
                const el = document.querySelector("#reject-modal");
                const modal = tailwind.Modal.getOrCreateInstance(el);
                modal.show();
            }
        </script>

        @if (!empty(session('success')))
        <script>
             window.onload = function() {
                // Toastify Success Message
            };
        </script>
        @endif
    </body>
</html>
