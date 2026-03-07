<x-mail::message>
# Welcome to Unique Foods, {{ $user->name }}! 🌿

Thank you for joining us. Your email has already been verified ✅

Here's what you can do now:

<x-mail::button :url="config('app.url')" color="green">
Start Shopping
</x-mail::button>

**What's waiting for you:**
- 🥦 Fresh & Organic Products
- 🚚 Fast Delivery to your doorstep
- 🎁 Exclusive member discounts
- 📦 Easy order tracking

If you have any questions, just reply to this email.

Thanks,
**The Unique Foods Team**
</x-mail::message>
