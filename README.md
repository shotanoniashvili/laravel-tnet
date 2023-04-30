# Setup
- `composer install`
- `cp .env.example .env`
- `touch database/database.sqlite`
- `php artisan migrate`
- `php artisan db:seed`

### Exercise File locations
- `app/Helpers`
- Exercise 1 - `ShortestWord.php`
- Exercise 2 - `ArrayElementCount.php`
- Exercise 3 - `ReplaceWithBrackets.php`
- Exercise 4 - `ExpandString.php`
- Exercise 5 - `RangeExtraction.php`
- Exercise 6 - `SnailArray.php`
---
(each exercise has its own test, located into `tests/Unit` directory)

### Cart Controller Location
- `app/Http/Controllers/CartController.php`
