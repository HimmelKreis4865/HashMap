# HashMaps
HashMaps is a custom PHP Library that allows support for Java HashMaps in PHP.

## Usage

```php
use HashMap\HashMap;

require "HashMap.php";

$map = new HashMap("String", "String");
```

when constructing `new HashMap()`, you're passing two arguments. 

The first argument is the expected type of the Key, this either be `String`, `Integer` or `Float` **not case-sensitive**

The second argument you pass is the expected type of the Value, you can see them in a table below

Type | Name | Alias
--- | --- | ---
string | String | Str
float | Float | -
int | Integer | Int
array | Array | -
callable | Callable | Function
resource | Resource | Res
null | Null | -
bool | Bool | Boolean
object | Object | Obj
double | Double | -
long | Long | -

=> To add custom classes (or objects) you gotta use `Classname::class`, e.g.: `Socket::class` 

**Not case-sensitive**

# Documentation
### Add a value to the hashmap
```php
$hashmap->put($key, $value);
```

### Get a value from the hashmap
```php
$value = $hashmap->get($key);
```

### Get a value from the hashmap with default values
```php
$value = $hashmap->getOrDefault($key, $default);
```

### Check if a key exists
```php
$hashmap->contains($key);
```

### Check if a value exists
```php
$hashmap->containsValue($value);
```

### Get Map size
```php
$hashmap->size();
```

### Remove a key from the Map
```php
$hashmap->remove($key);
```

### Go through all keys & values
```php
$hashmap->forEach(function($key, $value) {

});
```

### Get all values of the map
```php
$values = $hashmap->getValues();
```

### Get all keys of the map
```php
$keys = $hashmap->keySet();
```

### Replace a key in the map if the old value is not null
```php
$hashmap->replace($key, $value);
```

### Check if the map is empty
```php
$hashmap->isEmpty();
```

### Reset the whole map
```php
$hashmap->clear();
```