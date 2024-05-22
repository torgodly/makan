<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                    "بطاقات العمل",
                    "كتيبات",
                    "نشرات",
                    "بطاقات بريدية",
                    "ملصقات",
                    "لوحات إعلانية",
                    "لافتات",
                    "علامات",
                    "كتالوجات",
                    "قوائم الطعام",
                    "تغليف المنتجات",
                    "سلع ترويجية معروفة (مثل القمصان والقبعات)",
                    "تغليف المركبات",
                    "عروض عرض تجاري",
                    "عروض نقاط البيع",
                    "سلع ترويجية (مثل الأقلام والسلاسل الرئيسية)",
                    "لوحات خارجية (مثل لافتات الرصيف والأعلام)",
                    "ملصقات وملصقات",
                    "إعلانات مطبوعة",
                    "قطع البريد المباشر"
                ]
            ),
            'cost' => $this->faker->randomFloat(2, 1, 1000),
            'price' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
