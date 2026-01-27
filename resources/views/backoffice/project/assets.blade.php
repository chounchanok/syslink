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
                            <li class="breadcrumb-item"><a href="{{ route('project') }}">Project</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Asset Tracking</li>
                        </ol>
                    </nav>
                    @include('backoffice.menu.account_menu')
                </div>

                <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Project Asset Tracking
                    </h2>
                </div>

                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 box p-5">
                        <h3 class="font-medium text-base mb-4">Allocate Product to Project</h3>
                        <form action="{{ route('project.asset_create') }}" method="POST" class="flex flex-wrap gap-4 items-end">
                            @csrf
                            <div class="flex-1 min-w-[200px]">
                                <label class="form-label">Project</label>
                                <select name="project_id" class="form-select" required>
                                    <option value="">Select Project...</option>
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <label class="form-label">Product (Inventory)</label>
                                <select name="product_id" class="form-select" required>
                                    <option value="">Select Product...</option>
                                    @foreach($products as $pd)
                                        <option value="{{ $pd->id }}">{{ $pd->sku }} - {{ $pd->name }} (In Stock: {{ $pd->qty }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-24">
                                <label class="form-label">Qty</label>
                                <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                            </div>
                            <button class="btn btn-primary"> <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Allocate </button>
                        </form>
                    </div>

                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">PROJECT</th>
                                    <th class="whitespace-nowrap">PRODUCT NAME</th>
                                    <th class="whitespace-nowrap text-center">QTY</th>
                                    <th class="whitespace-nowrap text-center">STATUS</th>
                                    <th class="whitespace-nowrap">INSTALLED AT</th>
                                    <th class="whitespace-nowrap">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assets as $asset)
                                <tr class="intro-x">
                                    <td>
                                        <div class="font-medium">{{ $asset->project->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $asset->project->code }}</div>
                                    </td>
                                    <td>
                                        <div class="font-medium">{{ $asset->product->name ?? 'Unknown Product' }}</div>
                                        <div class="text-xs text-slate-500">{{ $asset->product->sku ?? '-' }}</div>
                                    </td>
                                    <td class="text-center">{{ $asset->quantity }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('project.asset_update_status') }}" method="POST" onchange="this.submit()">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $asset->id }}">
                                            <select name="status" class="form-select form-select-sm w-32 mx-auto
                                                {{ $asset->status == 'installed' ? 'text-success border-success' : '' }}
                                                {{ $asset->status == 'defective' ? 'text-danger border-danger' : '' }}
                                            ">
                                                <option value="pending_install" {{ $asset->status == 'pending_install' ? 'selected' : '' }}>Pending</option>
                                                <option value="installed" {{ $asset->status == 'installed' ? 'selected' : '' }}>Installed</option>
                                                <option value="configured" {{ $asset->status == 'configured' ? 'selected' : '' }}>Configured</option>
                                                <option value="defective" {{ $asset->status == 'defective' ? 'selected' : '' }}>Defective</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        {{ $asset->installed_at ? \Carbon\Carbon::parse($asset->installed_at)->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <form action="{{ route('project.asset_delete') }}" method="POST" onsubmit="return confirm('Remove this asset allocation?');">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $asset->id }}">
                                                <button class="flex items-center text-danger"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Remove </button>
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
                                {{ $assets->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @include('backoffice.js.js')
        @if (!empty(session('success')))
        <script>
             window.onload = function() {
                Toastify({
                    text: "Operation Completed",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#10B981",
                }).showToast();
            };
        </script>
        @endif
    </body>
</html>
