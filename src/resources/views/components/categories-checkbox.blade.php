@php
    $showQuickAdd = $showQuickAdd ?? true;
    $locale = $locale ?? app()->getLocale();
    $name = $name ?? 'categories[]';
    $hasParent = $hasParent ?? true;
@endphp

<div class="quick-add-category-container"
     data-store-url="{{ $storeUrl }}"
     data-locale="{{ $locale }}">
    <div class="categories-checkbox-list">
        @foreach($categories as $category)
            <div class="form-check" style="margin-left: {{ $level * 20 }}px;">
                <input class="form-check-input"
                       type="checkbox"
                       name="{{ $name }}"
                       value="{{ $category->id }}"
                       id="cat-{{ $category->id }}"
                        @checked(in_array($category->id, $selectedCategories ?? []))
                >
                <label class="form-check-label" for="cat-{{ $category->id }}">
                    {{ $category->name }}
                </label>
            </div>

            @if($category->children && $category->children->isNotEmpty())
                @component('admin::components.categories-checkbox', [
                    'categories' => $category->children,
                    'selectedCategories' => $selectedCategories ?? [],
                    'level' => $level + 1,
                    'showQuickAdd' => false,
                    'storeUrl' => $storeUrl,
                    'locale' => $locale,
                    'name' => $name,
                ])
                @endcomponent
            @endif
        @endforeach
    </div>

    @if($showQuickAdd && $level === 0)
        <div class="quick-add-category-form mt-3 p-2 border rounded" style="display: none;">
            <div class="form-group mb-2">
                <input type="text"
                       class="form-control form-control-sm quick-add-category-name"
                       placeholder="{{ __('admin::translation.category_name') }}">
            </div>

            @if($hasParent)
                <div class="form-group mb-2">
                    <select class="form-control form-control-sm quick-add-category-parent">
                        <option value="">{{ __('admin::translation.select_parent') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @if($category->children && $category->children->isNotEmpty())
                                @foreach($category->children as $child)
                                    <option value="{{ $child->id }}">-- {{ $child->name }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="d-flex justify-content-between">
                <button type="button"
                        class="btn btn-sm btn-success quick-add-category-save"
                        data-saving-text="{{ __('admin::translation.saving') }}"
                        data-input-name="{{ $name }}"
                >
                    <i class="fas fa-check"></i> {{ __('admin::translation.save') }}
                </button>
                <button type="button" class="btn btn-sm btn-secondary quick-add-category-cancel">
                    <i class="fas fa-times"></i> {{ __('admin::translation.cancel') }}
                </button>
            </div>
        </div>
        <div class="mt-2">
            <button type="button" class="btn btn-sm btn-primary btn-block quick-add-category-toggle">
                <i class="fas fa-plus"></i> {{ __('admin::translation.quick_add_category') }}
            </button>
        </div>
    @endif
</div>
