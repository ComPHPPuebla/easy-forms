# Creating form elements

The only required attribute for an element is its name. You can also set
choices for elements like selects, radio buttons and checkboxes.

```php
use EasyForms\Elements;

$name = new Elements\Text('name');
$password = new Elements\Password('password');
$userId = new Elements\Hidden('user_id');
$description = new Elements\TextArea('description');
$avatar = new Elements\File('avatar');
$termsAndConditions = new Elements\Checkbox('terms', $checkedValue = 'accept');
$gender = new Elements\Radio('gender', $choices = [
    'M' => 'Male', 'F' => 'Female'
]);
$position = new Elements\Select('position', $choices = [
    'b'=> 'Backend developer', 'f' => 'Frontend Developer'
]);
$interests = new Elements\MultiCheckbox('interests', $choices = [
    'u' => 'Usability', 's' => 'Security', 't' => 'Testing'
]);
```

Choices can be omitted in the constructor of elements like `Radio`, `Select`,
and `MultiCheckbox`, and injected later via the `setChoices` method.

```php
$gender->setChoices([
    'M' => 'Male', 'F' => 'Female'
]);
$position->setChoices([
    'b'=> 'Backend developer', 'f' => 'Frontend Developer'
]);
$interests->setChoices([
    'u' => 'Usability', 's' => 'Security', 't' => 'Testing'
]);
```

You can also make elements optional, and pass them error messages.

```php
$description->makeOptional();
$password->setMessages(['Please enter your password']);
```

Unlike other form components, elements are not responsible for validation
and filtering.
