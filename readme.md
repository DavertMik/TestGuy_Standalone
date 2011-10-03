# TestGuy Standalone 
## Functional testing suite

TestGuy is a functional testing framework powered by PHPUnit.
Designed make tests easy to write, read, and debug.

### Requirements
PHPUnit installed

## TestGuy principles
TestGuy library allows to write test scenarios in PHP with DSL designed to look like native English.
 
The main point in TestGuy philosophy is... a Test Guy! . Let's imagine it's our tester that comes to site. 

Test Guy performs multiple actions and sees the results.
TestGuy can test only things observable to common users. I.e. he can't see the internal application state, he sees only the results of his actions. If the results match the expected scenario test is passed.

### Installation (not complete)
1. Download (or git clone) TestGuy
2. Copy testguy.php to your project directory
3. Edit testguy.php and set proper path to file autoload.php in the root of TestGuy dir.
4. Execute php testguy.php console tool

## Running tests

{{{
./php testguy.php run
}}}


== Writing tests ==

- For best experience use IDE - Netbeans (free) or PHPStorm. It allows autocompletion that helps much writing tests.
- Test scenarios are stored in /test/testguy/frontend . They are suffixed with 'Spec'. 
- Create a new php file there and it will be automatically added to suite.
- Add {{{ $I = new TestGuy($scenario); }}} line first
- Write a scenario description {{{ $I->wantTo('describe what your scenario is doing'); }}} or {{{ $I->wantToTest('describe the feature you want to test'); }}}
- Try to write "$I->" on next line and you will see a dropdown of all actual methods.
- Write a scenario...


There are 3 types of TestGuy methods: 

1. conditions, starts with 'am' (for example 'amOnPage'). Are used to define the starting point of scenario.
2. assertions, starts with 'see' (for example 'see', or 'seeInDatabase'). Are used to check results. 
3. actions, all other methods. 


Basic scenario should start with conditions, perform some actions and then finish with assertions.

== API ==
Currently implemented methods 

- amOnPage($page) - moves browser to specified page
- am($name) - logs in as user with $name
- amMobilePhoneOwner($phone) - specifies that user can receive texts to $phone and send them to our numbers

- see($text) - looks for specific text on page. Second parameter can define CSS selector in which to look.
- seeLink()

- submitForm($selector, $params)

- seeInDatabase($model, $params) - check if record for $model and with specified data exists in database.

....