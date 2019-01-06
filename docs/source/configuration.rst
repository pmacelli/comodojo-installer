Configuration
=============

.. _comodojo/dispatcher default configuration: https://github.com/comodojo/dispatcher/blob/f0d4c8d87e8d986da67cb5b0f5d102113f774cca/composer.json#L34
.. _comodojo/dispatcher default configuration: https://github.com/comodojo/dispatcher/blob/f0d4c8d87e8d986da67cb5b0f5d102113f774cca/composer.json#L70

The comodojo-installer library reads configuration statements from two locations: project package and manageable bundles.

.. topic:: Project package

    The composer.json of the project package is the main location that contains both installer and framework configuration.

    The installer configuration is the first one that the installer reads, and it's used to configure its features (e.g. main configuration file name, supported bundles).

    The project configuration is evaluated at the end of the project installation, when the ``post-create-project-cmd`` composer event is emitted, and it provides the configuration for the entire project.

.. topic:: External bundles

    The composer.json of external bundles contains only the information about application components that will be installed including the package in the project.

    For example, the metadata needed to auto-configure routes into comodojo/dispatcher is in the bundle's composer.json file, under a specific configuration *stanza* in the *extra* field and following a specific pattern (for more information, see section :ref:`drivers`).

Installer Configuration
-----------------------

This section is used to configure the comodojo/installer and to extend its functionalities.

The configuration *stanza* inside the *extra* field is fixed and cannot be changed:

.. code-block:: json

    {
        "extra": {
            // this section name CANNOT change
            "comodojo-installer": {...},
            // this section name CAN change
            "comodojo-configuration": {...}
        }
    }

To understand different statements in this section, let's look at the `comodojo/dispatcher default configuration`_:

.. code-block:: json

    "comodojo-installer": {
        // what package types installer will look for?
        "package-types": [
            "comodojo-bundle"
        ],
        // this subsection tells installer how to manage the global configuration
        "global-config": {
            // the extra-field where look for configuration statements (see next topic)
            "extra-field": "comodojo-configuration",
            // how the configuration will be persisted
            "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
            // parameters to pass to the persister
            "params": {
                "config-file": "config/comodojo-configuration.yml",
                "depth": 6
            }
        },
        // this subsection instructs installer to search for specific extra
        // field when a package is recognized as manageable (package-type in [package-types])
        "package-extra": {
            // this defines that each valid package could include a routes field that will be used to
            // build the routing table of the dispatcher
            "routes": {
                // once found, route statements are processed by a RouteDriver...
                "driver": "\\Comodojo\\Installer\\Drivers\\RouteDriver",
                // ...and persisted using the YamlPersistence class...
                "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
                // ...using this parameters.
                "params": {
                    "config-file": "config/comodojo-routes.yml"
                }
            },
            "plugins": {
                "driver": "\\Comodojo\\Installer\\Drivers\\PluginDriver",
                "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
                "params": {
                    "config-file": "config/comodojo-plugins.yml"
                }
            },
            "commands": {
                "driver": "\\Comodojo\\Installer\\Drivers\\CommandDriver",
                "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
                "params": {
                    "config-file": "config/comodojo-commands.yml"
                }
            }
        }
    }

Project Configuration
---------------------

The second section contains the ``global-config``, the project's default configuration that will be persisted during project installation and then loaded according to project rules.

This section depends primarily on the project itself and the framework(s) behind it.

As an example, let's consider the `comodojo/dispatcher project configuration`_:

.. code-block:: json

    {
        "comodojo-configuration": {
            "static-config": "config",
            "routing-table-cache": true,
            "routing-table-ttl": 86400,
            "log": {
                "enable": true,
                "name": "dispatcher",
                "providers": {
                    "local" : {
                        "type": "StreamHandler",
                        "level": "info",
                        "stream": "logs/dispatcher.log"
                    }
                }
            },
            "cache": {
                "enable": true,
                "pick_mode": "PICK_FIRST",
                "providers": {
                    "local": {
                        "type": "Filesystem",
                        "cache_folder": "cache"
                    }
                }
            }
        }
    }
