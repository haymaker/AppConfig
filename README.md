# AppConfig Library
This library is a PHP library for a //very basic// group-key-value application configuration structure.  There are no real complex data types, only configuration groupings ('groups') and period-delimited keys with values.  The goal of this library is not to provide a sophisticated application configuration solution - its goal is to provide a fairly simple application configuration store.

## Prerequisites
  - PHP Yaml PECL Extension

## Prerequisite install
Debian Systems:
```bash
sudo apt-get install php5-cli php-pear php5-dev libyaml-dev
sudo pecl install pecl/yaml
````

Depending on the Debian version, EITHER:
```bash
sudo sh -c "echo 'extension=yaml.so' > /etc/php5/conf.d/20-yaml.ini"
```
OR:
```bash
sudo sh -c "echo 'extension=yaml.so' > /etc/php5/mods-available/yaml.ini"
sudo ln -s /etc/php5/mods-available/yaml.ini /etc/php5/conf.d/20-yaml.ini
```

## Usage
AppConfig has the ability to load configuration data from several sources, but this functionality is not quite complete.  For now, there is only one configuration loader, and that is for Yaml.

Currently, the functionality only supports the loading from a single source - so your configuration will need to be all in a single file.

When initializing the AppConfig object in the application, you will need to know the following information:

-  The path to your Yaml configuration file
-  The loader you will use (currently only YamlLoader)
-  All classes loaded (if you're using an autoloader, this shouldn't be a problem)

To instiantiate the object in your application, perform the following:

```php
$configFile = __DIR__.'/path/to/config.yml';

// Load after object instantiation
$config = new AppConfig();
$config->load(new YamlLoader($configFile));

// OR load during instantiation
$config = new AppConfig(new YamlLoader($configFile));

$configValue = $config->get('groupname', 'key.name');
```

## YAML Config File
The YAML configuration file is expected to be in the standard YAML format in a key: value representation.  The first level should be the configuration 'groups' in order to group configurations into separate domains.  The next levels should be set as key-values in depth with a final key equaling a final value.  For example:

```yaml
groupname1:
  topkey:
    secondarykey1: value1
    secondarykey2: value2
    secondarykey3: value3
  topkey2:
    anotherkey: anothervalue
groupname2:
  key1:
    key2:
      key3a: value
      key3b: value
```

... and so on.

There is currently no limit to the depth of keys/values, and you can provide a shorter key to the get method and have it return an array of all the children.

```php
$config = $config->get('groupname2', 'key1.key2');
```

The above would return an associative array of:
```php
$config = array(
  'key3a' => 'value',
  'key3b' => 'value'
);
```
