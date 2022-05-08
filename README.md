# maestro-orchid

Administration panel auto generation solution created by [MaestroError](https://www.linkedin.com/in/maestroerror/), Based on [Laravel Orchid Platform](https://github.com/orchidsoftware/platform)

### Usage
---

 First of all ensure that you have installed orchid-platform, follow installation [guide](https://orchid.software/en/docs/installation/)
---
1. run `php artisan vendor:publish --tag=morchid-config`
2. run `php artisan morchid:listmodel {modelname}`
3. edit new model in app/morchid/{modelname}.php
4. use Orchid's [Form elements](https://orchid.software/en/docs/field/) working with $objectFields
5. add new created listmodel in main menu manually, app/orchid/PlatformProvider.php registerMainMenu method's return array:
```
Menu::make('Posts')
                ->icon('monitor')
                ->route('morchid::list', ["model"=>"Posts"]),
```
 *if you are not familiar with orchid platform, you can choose icons from [here](https://orchid.software/en/docs/icons/) easily*

Note: your new list model needs table in DB with exact fields, which you added as keys in $objectFields property

#### Next Steps


- get fieldsNamespace from config file +
- Make stub and new command for mOrchid models generation +
- Need to find way, for adding permissions and main menu items in platform provider -> just adding manually
- test admin generation with mOrchid +
- refactor baseTrait

#### To Do

- Review all command and comment them
- Review main class and add comments
- Review routes, resources and other needed codes, find out why you wrote this and comment them

#### Issues

- php artisan morchid:listmodel {modelname} Command creates file with lowercase

##### State

- Main codebase transferred
- Main command created
- Main 3 stabs created 
- Routes are created 
- Autogeneration of listModel started, it works!
