<x-filament-widgets::widget>
    <x-filament::section heading="أحدث الأنشطة">
        <div class="space-y-2 text-sm">
            @forelse($this->getData()['activities'] as $act)
                <div class="flex items-start justify-between gap-4 rtl:space-x-reverse">
                    <div>
                        <span class="font-medium text-primary-600">{{ $act->log_name }}</span>
                        <span class="text-gray-600 dark:text-gray-300">— {{ $act->description }}</span>
                        @if($act->causer)
                            <span class="text-gray-500">(بواسطة: {{ $act->causer->name }})</span>
                        @endif
                    </div>
                    <span class="text-xs text-gray-400">{{ $act->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <div class="text-gray-500">لا توجد أنشطة بعد.</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
