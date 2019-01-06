Installation
============

.. _comodojo/dispatcher: https://github.com/comodojo/dispatcher
.. _comodojo/extender: https://github.com/comodojo/extender

.. note: This library is a default dependency of `comodojo/dispatcher`_ and `comodojo/extender`_ project packages.

As a composer plugin, this library must be included as a direct dependency in a root (project) package.

.. code:: javascript

    "require": {
        // other libraries
        // ...
        "comodojo/comodojo-installer" : "^1.0"
    }

Once installed, the plugin is immediately active and starts to scan the composer.json file looking for a custom configuration (in the *extra* section).

Following an example configuration in the composer.json.

.. code:: javascript

    "extra": {
        "comodojo-installer": {
            "package-types": [
                "comodojo-bundle"
            ],
            "global-config": {
                "extra-field": "comodojo-configuration",
                "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
                "params": {
                    "config-file": "config/comodojo-configuration.yml",
                    "depth": 6
                }
            },
            "package-extra": {
                "routes": {
                    "driver": "\\Comodojo\\Installer\\Drivers\\RouteDriver",
                    "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
                    "params": {
                        "config-file": "config/comodojo-routes.yml"
                    }
                }
            }
        }
    }

.. seealso:: More details in :ref:`general-configuration`.

Requirements
************

To work properly, comodojo/comodojo-installer requires:

- PHP >= 5.6.0
- composer-plugin-api > 1.0
