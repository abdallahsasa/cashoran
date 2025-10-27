<?php

namespace App\Services\Course;

use App\Models\Course\CourseCart;
use App\Models\Course\CourseCoupon;
use Illuminate\Database\Eloquent\Collection;

class CourseCartService
{
   public function isInCart(int $userId, int $courseId): bool
   {
      return CourseCart::where('user_id', $userId)
         ->where('course_id', $courseId)
         ->exists();
   }

   public function addToCart(int $userId, int $courseId): ?CourseCart
   {
      if (!$this->isInCart($userId, $courseId)) {
         return CourseCart::create([
            'user_id' => $userId,
            'course_id' => $courseId
         ]);
      }

      return null;
   }

   public function removeFromCart(int $userId, int $courseId): bool
   {
      return (bool) CourseCart::where('user_id', $userId)
         ->where('course_id', $courseId)
         ->delete();
   }

   public function getCartItems(int $userId)
   {
      return CourseCart::where('user_id', $userId)
         ->with('course')
         ->get();
   }

   public function calculateCart(Collection $cart, ?CourseCoupon $coupon): array
   {
      $subtotal = round($cart->sum(function ($item) {
         return $item->course->discount ? $item->course->discount_price : $item->course->price;
      }), 2);
      $sellingTax = app('system_settings')->fields['selling_tax'];
      $discountedPrice = round($subtotal - ($coupon ? $coupon->discount : 0), 2);
      $taxAmount = round(($discountedPrice * $sellingTax) / 100, 2);
      $totalPrice = round($discountedPrice + $taxAmount, 2);

      return [
         'subtotal' => $subtotal,
         'discountedPrice' => $discountedPrice,
         'taxAmount' => $taxAmount,
         'totalPrice' => $totalPrice
      ];
   }

   public function clearCart(int $userId): int
   {
      return CourseCart::where('user_id', $userId)->delete();
   }
}
