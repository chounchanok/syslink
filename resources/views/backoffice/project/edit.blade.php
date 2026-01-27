<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        @include('backoffice.head')
    </head>
    <?php $page="project" ?>
    <body class="py-5">
        @include('backoffice.menu.navbar-modile')
        <div class="flex">
            @include('backoffice.menu.navbar')
            <div class="content">
                <div class="top-bar">
                    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Application</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('project') }}">Project</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                    @include('backoffice.menu.account_menu')
                </div>

                <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Edit Project: {{ $project->code }}
                    </h2>
                </div>

                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-8">
                        <form action="{{ route('project.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $project->id }}">

                            <div class="intro-y box p-5">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 sm:col-span-6">
                                        <label class="form-label">Project Code</label>
                                        <input type="text" class="form-control" name="code" value="{{ $project->code }}" required>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <label class="form-label">Project Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $project->name }}" required>
                                    </div>

                                    <div class="col-span-12 sm:col-span-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" name="start_date" value="{{ \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" name="end_date" value="{{ \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') }}" required>
                                    </div>

                                    <div class="col-span-12 sm:col-span-6">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="planned" {{ $project->status == 'planned' ? 'selected' : '' }}>Planned</option>
                                            <option value="active" {{ $project->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="hold" {{ $project->status == 'hold' ? 'selected' : '' }}>On Hold</option>
                                            <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <label class="form-label">Color Tag</label>
                                        <input type="color" class="form-control h-10 p-1" name="color" value="{{ $project->color }}">
                                    </div>
                                </div>

                                <div class="text-right mt-5">
                                    <a href="{{ route('project') }}" class="btn btn-outline-secondary w-24 mr-1">Cancel</a>
                                    <button type="submit" class="btn btn-primary w-24">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('backoffice.js.js')
    </body>
</html>
