# Changelog

## 0.4.0 (2018-11-20)
- a script for packing plugin's ZIP archive added

## 0.3.0 (2018-11-19)
- unified the way classes are created, all classes now have static `create()` method
- added abstract `UcrmPluginSdkException`, all SDK exceptions extend from it

## 0.2.0 (2018-11-19)
- more tests added (coverage 100%)
- added `InvalidPluginRootPathException`, which is thrown instead of `ConfigurationException` when the plugin root path is wrongly configured

## 0.1.0 (2018-11-16)
Initial release.
