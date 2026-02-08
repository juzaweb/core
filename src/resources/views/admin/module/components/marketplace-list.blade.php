{{-- Marketplace List: {{ count($modules) }} modules --}}
@foreach ($modules as $module)
    <div class="col-md-4 p-2 marketplace-item">
        @component('core::admin.module.components.marketplace-item', ['module' => $module, 'installedModules' => $installedModules])
        @endcomponent
    </div>
@endforeach
