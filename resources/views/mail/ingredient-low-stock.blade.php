<x-mail::message>

# Low Stock Alert

Dear,

The following ingredient is low in stock:

{{ $ingredient->name }}

Regards,<br>
{{ config('app.name') }}
</x-mail::message>
