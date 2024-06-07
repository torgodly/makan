<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        @php
            $data = (object) collect($this->data)->toArray();
            $price = 0; // Initialize $price before the loop

            foreach ($data->items as $item) {
                $price += $item['price'] * (int) $item['quantity']; // Use += for cumulative addition

            }
            $price -= (float)$data->discount; // Subtract discount from the total price

        @endphp


        <p class="text-5xl text-black text-center" dir="rtl">{{ $price }}د.ل</p>
    </div>
</x-dynamic-component>
