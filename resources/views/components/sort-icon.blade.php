@if($sortField === $field)
    <span class="ml-1">
        @if($sortDirection === 'asc')
            ↑
        @else
            ↓
        @endif
    </span>
@endif