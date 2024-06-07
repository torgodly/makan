<x-print-layout :size="'A4'">
    <x-slot name="content">
        <section class="sheet padding-10mm !pt-5 break-words" dir="rtl">
            <div class="flex justify-between items-center border-b border-neutral-300 ">
                <section class="flex items-center justify-center mb-5">
                    <x-application-logo class="!h-20"/>
                </section>

                <section>
                    <h1 class="pb-3 text-neutral-700  text-center">فاتورة</h1>
                </section>
            </div>

            <section class="mb-5">
                <div class="flex justify-between">
                    <div class="text-right">
                        <h2 class="font-bold text-xl">شركة مكان للدعاية والاعلان</h2>
                        <p>طرابلس حي الاندلس</p>
                        <p>البريد الإلكتروني: makan@example.com</p>
                        <p>الهاتف: 0911111111</p>
                    </div>
                    <div class="text-left">
                        <h2 class="font-bold text-xl">فاتورة #{{$order->order_number}}</h2>
                        <p>التاريخ: {{$order->created_at->format('d-m-Y')}}</p>
                    </div>
                </div>
            </section>

            <section class="mb-5">
                <div class="border border-gray-300 p-4 rounded-lg">
                    <h2 class="font-bold text-lg">فاتورة إلى:</h2>
                    <p>{{$order->customer->name}}</p>
                    <p>البريد الإلكتروني: {{$order->customer->email}}</p>
                    <p>الهاتف: {{$order->customer->phone}}</p>
                </div>
            </section>

            <section>
                <x-panel>
                    <x-slot name="slot" class="!p-0 overflow-auto">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-2 px-4 border-b font-bold text-neutral-950 text-right">الوصف</th>
                                    <th class="py-2 px-4 border-b font-bold text-neutral-950 text-right">الكمية</th>
                                    <th class="py-2 px-4 border-b font-bold text-neutral-950 text-right">سعر الوحدة</th>
                                    <th class="py-2 px-4 border-b font-bold text-neutral-950 text-right">الإجمالي</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="py-2 px-4 border-b text-neutral-950 text-right">{{$item->product->name}}</td>
                                        <td class="py-2 px-4 border-b text-neutral-950 text-right">{{$item->quantity}}</td>
                                        <td class="py-2 px-4 border-b text-neutral-950 text-right">{{$item->product->price}}</td>
                                        <td class="py-2 px-4 border-b text-neutral-950 text-right">{{$item->product->price * $item->quantity}}</td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </x-slot>
                </x-panel>
            </section>

            <section class="flex justify-end mt-5">
                <div class="text-right">
                    <p class="text-lg font-bold">المجموع الفرعي: {{$order->total_price + $order->discount . ' د.ل'}}</p>
                    <p class="text-lg font-bold">الخصم: {{$order->discount . ' د.ل'}}</p>
                    <p class="text-xl font-bold">الإجمالي: {{$order->total_price . ' د.ل'}}</p>
                </div>
            </section>

            <section class="mt-12 text-xs text-neutral-500">
                <div class="flex justify-between pt-3 border-t border-neutral-300">
                    <div>
                        <span>تمت الطباعة بتاريخ:</span>
                        <span dir="ltr">{{now()}}</span>
                    </div>

                    <div>
                        <span>تمت الطباعة بواسطة:</span>
                        <span>{{auth()->user()->name}}</span>
                    </div>
                </div>
            </section>
        </section>
    </x-slot>
</x-print-layout>
