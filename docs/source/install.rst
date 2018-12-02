Installation
============

.. _dispatcher: https://github.com/comodojo/dispatcher
.. _extender: https://github.com/comodojo/extender

.. note: This library is a default dependency of `dispatcher`_ and `extender`_ project packages.

To add it in personal projects, the first step is to add a specific requirement in the composer.json of the project package:

.. code:: javascript

    "require": {
        // other libraries
        // ...
        "comodojo/comodojo-installer" : "^1.0"
    }

The same file should include a specific configuration section in the *extra* field like:

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

More details about configuration statements are availble in :ref:`general-configuration`.

Requirements
************

To work properly, comodojo/comodojo-installer requires PHP >=5.6.0 and composer-plugin-api > 1.0
