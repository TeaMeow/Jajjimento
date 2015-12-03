# Jajjimento
風紀委員 - The form validation class in PHP.
```php
$jaji->source()
     ->add('username')->type('length')->min(3)->max(16)->required()
     ->add('age')     ->type('range') ->min(0)->max(12);
```
