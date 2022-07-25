<div class="double-panel">
    @for ($i = 0; $i < count($names); $i++)
        @component('components.employee.panel', ['name' => $names[$i], 'icon' => $icons[$i], 'text' => $texts[$i]])
        @endcomponent
    @endfor
</div>
